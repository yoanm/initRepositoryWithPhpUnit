<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

abstract class AbstractNodeUpdater
{
    /** @var AbstractNodeUpdater[] */
    private $updateDelegateList;
    /** @var HeaderFooterHelper */
    private $headerFooterHelper;

    /**
     * @param HeaderFooterHelper    $headerFooterHelper
     * @param AbstractNodeUpdater[] $updaterDelegateList
     */
    public function __construct(HeaderFooterHelper $headerFooterHelper, array $updaterDelegateList = [])
    {
        $this->updateDelegateList = $updaterDelegateList;
        $this->headerFooterHelper = $headerFooterHelper;
    }

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return ConfigurationItemInterface
     */
    abstract public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return bool
     */
    abstract public function supports(ConfigurationItemInterface $item);

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return bool
     */
    abstract public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface[] $itemList
     *
     * @return ConfigurationItemInterface
     */
    public function update(array $itemList)
    {
        $newItem = array_pop($itemList);
        while ($baseItem = array_pop($itemList)) {
            $newItem = $this->merge($baseItem, $newItem);
        }

        return $newItem;
    }

    /**
     * @param ConfigurationItemInterface[] $baseItemList
     * @param ConfigurationItemInterface[] $newItemList
     *
     * @return ConfigurationItemInterface[]
     */
    protected function mergeItemList(array $baseItemList, array $newItemList)
    {
        $groupedBaseNodeList = $this->groupItemList($baseItemList);
        /** @var Block[] $supportedNewNodeList */
        $supportedNewNodeList = $this->groupItemList($newItemList, true);

        $updatedItemList = [];
        while ($groupedBaseNode = array_shift($groupedBaseNodeList)) {
            if ($groupedBaseNode instanceof Block) {
                list($updatedItemList, $supportedNewNodeList) = $this->updateAndMergeBlock(
                    $groupedBaseNode,
                    $supportedNewNodeList,
                    $updatedItemList
                );
            } else {
                $updatedItemList[] = $groupedBaseNode;
            }
        }
        if (count($supportedNewNodeList)) {
            $updatedItemList = $this->appendNewNodeList($supportedNewNodeList, $updatedItemList);
        }

        return $updatedItemList;
    }

    /**
     * @param ConfigurationItemInterface[] $itemList
     * @param bool|false                   $supportedOnly
     *
     * @return \DOMNode[]|Block[]
     */
    protected function groupItemList(array $itemList, $supportedOnly = false)
    {
        $groupedItemList = [];
        while ($item = array_shift($itemList)) {
            if ($this->getUpdater($item, false)) {
                // Check if header comment exist if previous nodes
                $headerNodeList = $this->headerFooterHelper->extractHeaderOrLeadingSpaceNode($groupedItemList);
                $groupedItemList = $this->headerFooterHelper->updateListIfHeader($headerNodeList, $groupedItemList);
                // Check if footer comment exist if base node list
                $footerNodeList = $this->headerFooterHelper->extractNodeFooterList($itemList, $headerNodeList);
                $itemList = $this->headerFooterHelper->updateListIfFooter($itemList, $footerNodeList);

                $groupedItemList[] = new Block($item, $headerNodeList, $footerNodeList);
            } else {
                $groupedItemList[] = $item;
            }
        }

        if (true === $supportedOnly) {
            return array_filter($groupedItemList, function ($item) {
                return $item instanceof Block;
            });
        }

        return $groupedItemList;
    }

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return ConfigurationItemInterface
     */
    protected function mergeNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        if ($newItem instanceof UnmanagedNode) {
            return $newItem;
        }
        try {
            $updater = $this->getUpdater($newItem);
        } catch (\Exception $exception) {
            return $newItem;
        }

        return $updater->update([$baseItem, $newItem]);
    }

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return AbstractNodeUpdater|null
     *
     * @throws \Exception
     */
    protected function getUpdater(ConfigurationItemInterface $item, $throwException = true)
    {
        foreach ($this->updateDelegateList as $delegate) {
            if ($delegate->supports($item)) {
                return $delegate;
            }
        }

        if (true !== $throwException) {
            return null;
        }

        throw new \Exception(sprintf(
            'No update found for item %s',
            get_class($item)
        ));
    }

    /**
     * @param Block                        $supportedNewNode
     * @param Block                        $groupedBaseNode
     * @param ConfigurationItemInterface[] $updatedItemList
     *
     * @return ConfigurationItemInterface[]
     */
    protected function mergeBlock(Block $groupedBaseNode, array $updatedItemList, Block $supportedNewNode = null)
    {
        $updatedItemList = $this->headerFooterHelper->mergeHeaderNodeList(
            $groupedBaseNode->getHeaderNodeList(),
            $updatedItemList
        );
        $updatedItemList[] = $supportedNewNode
            ? $this->mergeNode(
                $groupedBaseNode->getItem(),
                $supportedNewNode->getItem()
            )
            : $groupedBaseNode->getItem();

        //return $updatedItemList;
        return $this->headerFooterHelper->mergeFooterNodeList(
            $groupedBaseNode->getFooterNodeList(),
            $updatedItemList
        );
    }

    /**
     * @param Block[]                      $supportedNewNodeList
     * @param ConfigurationItemInterface[] $updatedItemList
     *
     * @return ConfigurationItemInterface[]
     */
    protected function appendNewNodeList(array $supportedNewNodeList, array $updatedItemList)
    {
        // 1 - Remove trailing non block object (spaces and comments)
        $trailingNonBlockNodeList = [];
        while ($node = array_pop($updatedItemList)) {
            if (!$node instanceof \DOMNode
                || $node->nodeType === XML_ELEMENT_NODE
            ) {
                $trailingNonBlockNodeList[] = $node;
                break;
            }
            $trailingNonBlockNodeList[] = $node;
        }
        // 2 - Append remaining new node
        foreach ($supportedNewNodeList as $supportedNewNode) {
            $updatedItemList = $this->mergeBlock($supportedNewNode, $updatedItemList);
        }
        // 3 - Re append previously removed trailing non block objects
        foreach (array_reverse($trailingNonBlockNodeList) as $trailingNonBlockNode) {
            $updatedItemList[] = $trailingNonBlockNode;
        }
        return $updatedItemList;
    }

    /**
     * @param Block                        $groupedBaseNode
     * @param Block[]                      $supportedNewNodeList
     * @param ConfigurationItemInterface[] $updatedItemList
     *
     * @return array
     */
    protected function updateAndMergeBlock(
        Block $groupedBaseNode,
        array $supportedNewNodeList,
        array $updatedItemList
    ) {
        $key = $this->getSameNodeKey($groupedBaseNode, $supportedNewNodeList);
        if (null !== $key) {
            $updatedItemList = $this->mergeBlock($groupedBaseNode, $updatedItemList, $supportedNewNodeList[$key]);
            unset($supportedNewNodeList[$key]);
        } else {
            $updatedItemList = $this->mergeBlock($groupedBaseNode, $updatedItemList);
        }

        return [
            $updatedItemList,
            $supportedNewNodeList
        ];
    }

    /**
     * @param Block   $groupedBaseNode
     * @param Block[] $supportedNewNodeList
     *
     * @return null|int
     */
    protected function getSameNodeKey(Block $groupedBaseNode, array $supportedNewNodeList)
    {
        $key = null;
        $updater = $this->getUpdater($groupedBaseNode->getItem(), false);
        if ($updater) {
            foreach ($supportedNewNodeList as $supportedNewNodeKey => $potentialNewNode) {
                if ($updater->isSameNode($groupedBaseNode->getItem(), $potentialNewNode->getItem())) {
                    $key = $supportedNewNodeKey;
                    break;
                }
            }
        }
        return $key;
    }
}

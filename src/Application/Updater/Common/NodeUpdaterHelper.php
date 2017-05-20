<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class NodeUpdaterHelper
{
    /** @var HeaderFooterHelper */
    private $headerFooterHelper;

    /**
     * @param HeaderFooterHelper    $headerFooterHelper
     */
    public function __construct(HeaderFooterHelper $headerFooterHelper)
    {
        $this->headerFooterHelper = $headerFooterHelper;
    }

    /**
     * @param ConfigurationItemInterface[]  $baseItemList
     * @param ConfigurationItemInterface[]  $newItemList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return ConfigurationItemInterface[]
     */
    public function mergeItemList(
        array $baseItemList,
        array $newItemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        $groupedBaseNodeList = $this->groupItemList($baseItemList, $delegatedNodeUpdater);
        /** @var Block[] $supportedNewNodeList */
        $supportedNewNodeList = $this->groupItemList($newItemList, $delegatedNodeUpdater, true);

        $updatedItemList = [];
        while ($groupedBaseNode = array_shift($groupedBaseNodeList)) {
            if ($groupedBaseNode instanceof Block) {
                list($updatedItemList, $supportedNewNodeList) = $this->updateAndMergeBlock(
                    $groupedBaseNode,
                    $supportedNewNodeList,
                    $updatedItemList,
                    $delegatedNodeUpdater
                );
            } else {
                $updatedItemList[] = $groupedBaseNode;
            }
        }
        if (count($supportedNewNodeList)) {
            $updatedItemList = $this->appendNewNodeList(
                $supportedNewNodeList,
                $updatedItemList,
                $delegatedNodeUpdater
            );
        }

        return $updatedItemList;
    }

    /**
     * @param ConfigurationItemInterface[]  $itemList
     * @param bool|false                    $supportedOnly
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return \DOMNode[]|Block[]
     */
    public function groupItemList(
        array $itemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater,
        $supportedOnly = false
    ) {
        $groupedItemList = $this->doItemGrouping($itemList, $delegatedNodeUpdater);

        if (true === $supportedOnly) {
            return array_filter($groupedItemList, function ($item) {
                return $item instanceof Block;
            });
        }

        return $groupedItemList;
    }

    /**
     * @param Block                         $supportedNewNode
     * @param Block                         $groupedBaseNode
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     * @param ConfigurationItemInterface[]  $updatedItemList
     *
     * @return ConfigurationItemInterface[]
     */
    public function mergeBlock(
        Block $groupedBaseNode,
        array $updatedItemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater,
        Block $supportedNewNode = null
    ) {
        $updatedItemList = $this->headerFooterHelper->mergeHeaderNodeList(
            $groupedBaseNode->getHeaderNodeList(),
            $updatedItemList
        );
        $updatedItemList[] = $supportedNewNode
            ? $this->mergeNode(
                $groupedBaseNode->getItem(),
                $supportedNewNode->getItem(),
                $delegatedNodeUpdater
            )
            : $groupedBaseNode->getItem();

        //return $updatedItemList;
        return $this->headerFooterHelper->mergeFooterNodeList(
            $groupedBaseNode->getFooterNodeList(),
            $updatedItemList
        );
    }

    /**
     * @param Block[]                       $supportedNewNodeList
     * @param ConfigurationItemInterface[]  $updatedItemList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return ConfigurationItemInterface[]
     */
    protected function appendNewNodeList(
        array $supportedNewNodeList,
        array $updatedItemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
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
            $updatedItemList = $this->mergeBlock($supportedNewNode, $updatedItemList, $delegatedNodeUpdater);
        }
        // 3 - Re append previously removed trailing non block objects
        foreach (array_reverse($trailingNonBlockNodeList) as $trailingNonBlockNode) {
            $updatedItemList[] = $trailingNonBlockNode;
        }
        return $updatedItemList;
    }

    /**
     * @param Block                         $groupedBaseNode
     * @param Block[]                       $supportedNewNodeList
     * @param ConfigurationItemInterface[]  $updatedItemList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return array
     */
    protected function updateAndMergeBlock(
        Block $groupedBaseNode,
        array $supportedNewNodeList,
        array $updatedItemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        $key = $this->getSameNodeKey($groupedBaseNode, $supportedNewNodeList, $delegatedNodeUpdater);

        if (null !== $key) {
            $updatedItemList = $this->mergeBlock(
                $groupedBaseNode,
                $updatedItemList,
                $delegatedNodeUpdater,
                $supportedNewNodeList[$key]
            );
            unset($supportedNewNodeList[$key]);
        } else {
            $updatedItemList = $this->mergeBlock($groupedBaseNode, $updatedItemList, $delegatedNodeUpdater);
        }

        return [
            $updatedItemList,
            $supportedNewNodeList
        ];
    }

    /**
     * @param array                         $itemList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     * @return array
     * @throws \Exception
     */
    protected function doItemGrouping(array $itemList, DelegatedNodeUpdaterInterface $delegatedNodeUpdater)
    {
        $groupedItemList = [];
        while ($item = array_shift($itemList)) {
            if ($delegatedNodeUpdater->getUpdater($item, false)) {
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

        return $groupedItemList;
    }

    /**
     * @param Block                         $groupedBaseNode
     * @param Block[]                       $supportedNewNodeList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return null|int
     */
    protected function getSameNodeKey(
        Block $groupedBaseNode,
        array $supportedNewNodeList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        $key = null;
        $updater = $delegatedNodeUpdater->getUpdater($groupedBaseNode->getItem(), false);
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

    /**
     * @param ConfigurationItemInterface    $baseItem
     * @param ConfigurationItemInterface    $newItem
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return ConfigurationItemInterface
     */
    protected function mergeNode(
        ConfigurationItemInterface $baseItem,
        ConfigurationItemInterface $newItem,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        if ($newItem instanceof UnmanagedNode) {
            return $newItem;
        }
        try {
            $updater = $delegatedNodeUpdater->getUpdater($newItem);
        } catch (\Exception $exception) {
            return $newItem;
        }

        return $updater->update([$baseItem, $newItem]);
    }
}

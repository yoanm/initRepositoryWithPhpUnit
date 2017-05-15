<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Filter;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\Block;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class WhiteListUpdater extends AbstractNodeUpdater
{
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater         $attributeUpdater
     * @param WhiteListItemUpdater     $whiteListItemUpdater
     * @param ExcludedWhiteListUpdater $excludedWhiteListUpdater
     * @param NodeUpdaterHelper        $nodeUpdaterHelper
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        WhiteListItemUpdater $whiteListItemUpdater,
        ExcludedWhiteListUpdater $excludedWhiteListUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$whiteListItemUpdater, $excludedWhiteListUpdater]);
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param WhiteList $baseItem
     * @param WhiteList $newItem
     *
     * @return WhiteList
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        $itemList = $this->getNodeUpdaterHelper()->mergeItemList(
            $baseItem->getItemList(),
            $newItem->getItemList(),
            $this
        );

        return new WhiteList(
            $this->reorder($itemList),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof WhiteList;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }

    /**
     * @param ConfigurationItemInterface[] $itemList
     *
     * @return ConfigurationItemInterface[]
     */
    private function reorder(array $itemList)
    {
        $groupedItemList = $this->getNodeUpdaterHelper()->groupItemList($itemList, $this);
        // Try to move excluded node at end
        list($blockList, $excludedNodeBlock) = $this->extractItemListAndExcluded($groupedItemList);
        if ($excludedNodeBlock) {
            return $this->recomputeBlockList(
                $this->appendExcludedNodeBlock($blockList, $excludedNodeBlock)
            );
        }

        return $itemList;
    }

    /**
     * @param \DOMNode[]|Block[] $groupedItemList
     *
     * @return array
     */
    private function extractItemListAndExcluded(array $groupedItemList)
    {
        $excludedNodeBlock = null;
        $blockList = [];
        foreach ($groupedItemList as $block) {
            if ($block instanceof Block && $block->getItem() instanceof ExcludedWhiteList) {
                $excludedNodeBlock = $block;
            } else {
                $blockList[] = $block;
            }
        }

        return [
            $blockList,
            $excludedNodeBlock,
        ];
    }

    /**
     * @param \DOMNode[]|Block[] $blockList
     * @param Block              $excludedNodeBlock
     *
     * @return \DOMNode[]|Block[]
     */
    private function appendExcludedNodeBlock(array $blockList, Block $excludedNodeBlock)
    {
        // 1 - Remove trailing unmanaged node (spaces and comments)
        $trailingNonBlockNodeList = [];
        while ($node = array_pop($blockList)) {
            if (!$node instanceof Block) {
                $trailingNonBlockNodeList[] = $node;
            } else {
                $blockList[] = $node;
                break;
            }
        }
        // 2 - Append node
        $blockList[] = $excludedNodeBlock;
        // 3 - Re append previously removed trailing non block objects
        foreach (array_reverse($trailingNonBlockNodeList) as $trailingNonBlockNode) {
            $blockList[] = $trailingNonBlockNode;
        }

        return $blockList;
    }

    /**
     * @param \DOMNode[]|Block[] $blockList
     *
     * @return \DOMNode[]
     */
    private function recomputeBlockList(array $blockList)
    {
        $list = [];
        foreach ($blockList as $block) {
            if ($block instanceof Block) {
                $list = $this->getNodeUpdaterHelper()->mergeBlock(
                    $block,
                    $list,
                    $this
                );
            } else {
                $list[] = $block;
            }
        }

        return $list;
    }
}

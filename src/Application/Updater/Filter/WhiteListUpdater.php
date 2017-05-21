<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Filter;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
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
        $itemList = $this->getNodeUpdaterHelper()->mergeBlockList(
            $baseItem->getBlockList(),
            $newItem->getBlockList(),
            $this
        );

        return new WhiteList(
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList()),
            $this->reorder($itemList)
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
     * @param Block[] $itemList
     *
     * @return Block[]
     */
    private function reorder(array $itemList)
    {
        // Try to move excluded node at end
        list($blockList, $excludedNodeBlock) = $this->extractItemListAndExcluded($itemList);
        if ($excludedNodeBlock) {
            return $this->getNodeUpdaterHelper()->appendBeforeTrailingBlock([$excludedNodeBlock], $blockList);
        }

        return $itemList;
    }

    /**
     * @param Block[] $itemList
     *
     * @return array
     */
    private function extractItemListAndExcluded(array $itemList)
    {
        $excludedNodeBlock = null;
        $blockList = [];
        foreach ($itemList as $block) {
            if ($block->getItem() instanceof ExcludedWhiteList) {
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
     * @param Block[] $blockList
     * @param Block   $excludedNodeBlock
     *
     * @return Block[]
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
}

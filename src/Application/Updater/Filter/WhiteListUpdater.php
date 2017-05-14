<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Filter;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class WhiteListUpdater extends AbstractNodeUpdater
{
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater $attributeUpdater
     * @param WhiteListItemUpdater $whiteListItemUpdater
     * @param ExcludedWhiteListUpdater $excludedWhiteListUpdater
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        WhiteListItemUpdater $whiteListItemUpdater,
        ExcludedWhiteListUpdater $excludedWhiteListUpdater
    ) {
        parent::__construct([$whiteListItemUpdater, $excludedWhiteListUpdater]);
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
        $itemList = $this->mergeItemList($baseItem->getItemList(), $newItem->getItemList());

        return new WhiteList(
            $this->reorder($itemList),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
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
     * @param array $itemList
     */
    private function reorder(array $itemList)
    {
        // Try to move excluded node at end
        $groupedItemList = $this->groupItemList($itemList);
        $excludedNodeBlock = null;
        $newItemList = [];
        foreach ($groupedItemList as $block) {
            if ($block instanceof Block) {
                if ($block->getItem() instanceof ExcludedWhiteList) {
                    $excludedNodeBlock = $block;
                } else {
                    $newItemList = $this->mergeBlock($block, $newItemList);
                }
            } else {
                $newItemList[] = $block;
            }
        }
        if ($excludedNodeBlock) {
            // 1 - Remove trailing unmanaged node (spaces and comments)
            $trailingNonBlockNodeList = [];
            while($node = array_pop($newItemList)) {
                if ($node instanceof UnmanagedNode) {
                    $trailingNonBlockNodeList[] = $node;
                } else {
                    $newItemList[] = $node;
                    break;
                }
            }
            // 2 - Append node
            $newItemList = $this->mergeBlock($excludedNodeBlock, $newItemList);
            // 3 - Re append previously removed trailing non block objects
            foreach (array_reverse($trailingNonBlockNodeList) as $trailingNonBlockNode) {
                $newItemList[] = $trailingNonBlockNode;
            }

        }

        return $newItemList;
    }
}

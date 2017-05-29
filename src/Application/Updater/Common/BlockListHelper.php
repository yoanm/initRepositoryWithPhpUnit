<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class BlockListHelper
{
    /**
     * @param Block[] $blockList
     *
     * @return ConfigurationItemInterface[]
     */
    public function expandBlockList(array $blockList)
    {
        $list = [];
        foreach ($blockList as $block) {
            foreach ($block->getHeaderNodeList() as $header) {
                $list[] = $header;
            }
            if ($block->getItem()) {
                $list[] = $block->getItem();
            }
            foreach ($block->getFooterNodeList() as $footer) {
                $list[] = $footer;
            }
        }

        return $list;
    }

    /**
     * @param Block[] $blockToAppendList
     * @param Block[] $initialBlockList
     *
     * @return Block[]
     */
    public function appendBeforeTrailingBlock(
        array $blockToAppendList,
        array $initialBlockList
    ) {
        // 1 - Remove trailing block object (spaces and comments)
        $trailingBlockList = [];
        /** @var Block $node */
        while ($node = array_pop($initialBlockList)) {
            if (0 !== count($node->getFooterNodeList())
                || !$node->getItem() instanceof UnmanagedNode
                || !$node->getItem()->getValue() instanceof \DOMText
            ) {
                $initialBlockList[] = $node;
                break;
            } else {
                $trailingBlockList[] = $node;
            }
        }
        // 2 - Append remaining new node
        foreach ($blockToAppendList as $block) {
            $initialBlockList[] = $block;
        }
        // 3 - Re append previously removed trailing block objects
        foreach (array_reverse($trailingBlockList) as $trailingBlock) {
            $initialBlockList[] = $trailingBlock;
        }

        return $initialBlockList;
    }
}

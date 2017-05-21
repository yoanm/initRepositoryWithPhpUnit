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
     * @param Block[]                       $baseBlockList
     * @param Block[]                       $newBlockList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return Block[]
     */
    public function mergeBlockList(
        array $baseBlockList,
        array $newBlockList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        $newBlockList = array_filter($newBlockList, function (Block $block) {
            return !$block->getItem() instanceof UnmanagedNode;
        });

        /** @var Block[] $updatedItemList */
        $updatedItemList = [];
        while ($baseBlock = array_shift($baseBlockList)) {
            $key = $this->getSameNodeKey($baseBlock, $newBlockList, $delegatedNodeUpdater);
            $updatedItemList[] = isset($newBlockList[$key])
                ? $this->mergeBlock(
                    $baseBlock,
                    $delegatedNodeUpdater,
                    $newBlockList[$key]
                )
                : $baseBlock
            ;

            if (null !== $key) {
                unset($newBlockList[$key]);
            }
        }
        if (count($newBlockList)) {
            $updatedItemList = $this->appendBeforeTrailingBlock(
                $newBlockList,
                $updatedItemList
            );
        }

        return $updatedItemList;
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
            if (!$node->getItem() instanceof UnmanagedNode) {
                $initialBlockList[] = $node;
                break;
            } else {
                /** @var UnmanagedNode $unmanagedNode */
                $unmanagedNode = $node->getItem();
                $footerNodeList = $node->getFooterNodeList();
                /** @var UnmanagedNode|null $potentialEndTextNode */
                $potentialEndTextNode = array_pop($footerNodeList);
                if ($potentialEndTextNode && $potentialEndTextNode->getValue()->nodeType === XML_TEXT_NODE) {
                    // If footer node exist and it's a text node
                    // => Keep it to append it after new nodes but keep the rest of block above new nodes
                    $trailingBlockList[] = new Block($potentialEndTextNode);
                    $initialBlockList[] = new Block(
                        $unmanagedNode,
                        $node->getHeaderNodeList(),
                        $footerNodeList
                    );
                    break;
                }
                $trailingBlockList[] = $node;
                if (!$potentialEndTextNode && $unmanagedNode->getValue()->nodeType === XML_TEXT_NODE) {
                    // If current node has no footer and is a text node => Keep it to append it after new nodes
                    break;
                }
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

    /**
     * @param Block                         $baseBlock
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     * @param Block|null                    $newBlock
     *
     * @return Block
     */
    protected function mergeBlock(
        Block $baseBlock,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater,
        Block $newBlock
    ) {
//        $updatedItemList = $this->headerFooterHelper->mergeHeaderNodeList(
//            $baseBlock->getHeaderNodeList(),
//            $updatedItemList
//        );
        return new Block(
            $this->mergeItem(
                $baseBlock->getItem(),
                $newBlock->getItem(),
                $delegatedNodeUpdater
            ),
            $baseBlock->getHeaderNodeList(),
            $baseBlock->getFooterNodeList()
        );

        //return $updatedItemList;
//        return $this->headerFooterHelper->mergeFooterNodeList(
//            $baseBlock->getFooterNodeList(),
//            $updatedItemList
//        );
    }

    /**
     * @param Block                         $baseBlock
     * @param Block[]                       $newBlockList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return null|int
     */
    protected function getSameNodeKey(
        Block $baseBlock,
        array $newBlockList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        $key = null;

        if ($updater = $delegatedNodeUpdater->getUpdater($baseBlock->getItem(), false)) {
            foreach ($newBlockList as $newBlockKey => $potentialNewNode) {
                if ($updater->isSameNode($baseBlock->getItem(), $potentialNewNode->getItem())) {
                    $key = $newBlockKey;
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
    protected function mergeItem(
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

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class NodeUpdaterHelper
{
    /** @var BlockListHelper */
    private $blockListHelper;

    /**
     * @param BlockListHelper $blockListHelper
     */
    public function __construct(BlockListHelper $blockListHelper)
    {
        $this->blockListHelper = $blockListHelper;
    }

    /**
     * @param Block[]                          $baseItemList
     * @param Block[]                          $newItemList
     * @param DelegatedNodeUpdaterInterface    $delegatedNodeUpdater
     * @param BlockOrderDelegateInterface|null $blockOrderDelegate
     *
     * @return ConfigurationItemInterface[]
     */
    public function mergeBlockList(
        array $baseItemList,
        array $newItemList,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater,
        BlockOrderDelegateInterface $blockOrderDelegate = null
    ) {
        $blockList = $this->doMergeBlockList(
            $baseItemList,
            $newItemList,
            $delegatedNodeUpdater
        );

        if ($blockOrderDelegate) {
            $blockList = $this->reorder($blockOrderDelegate, $blockList);
        }

        return $blockList;
    }

    /**
     * @param Block[]                       $baseBlockList
     * @param Block[]                       $newBlockList
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return Block[]
     */
    protected function doMergeBlockList(
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
                    $newBlockList[$key],
                    $delegatedNodeUpdater
                )
                : $baseBlock
            ;
            // Do it even if key is not present => avoid a useless if
            unset($newBlockList[$key]);
        }
        if (count($newBlockList)) {
            $updatedItemList = $this->blockListHelper->appendBeforeTrailingBlock(
                $newBlockList,
                $updatedItemList
            );
        }

        return $updatedItemList;
    }

    /**
     * @param Block                         $baseBlock
     * @param Block|null                    $newBlock
     * @param DelegatedNodeUpdaterInterface $delegatedNodeUpdater
     *
     * @return Block
     */
    protected function mergeBlock(
        Block $baseBlock,
        Block $newBlock,
        DelegatedNodeUpdaterInterface $delegatedNodeUpdater
    ) {
        // Merge only main item, old header and footer are kept
        // Except if no base header/footer exist
        return new Block(
            $this->mergeItem(
                $baseBlock->getItem(),
                $newBlock->getItem(),
                $delegatedNodeUpdater
            ),
            0 === count($baseBlock->getHeaderNodeList())
                ? $newBlock->getHeaderNodeList()
                : $baseBlock->getHeaderNodeList(),
            0 === count($baseBlock->getFooterNodeList())
                ? $newBlock->getFooterNodeList()
                : $baseBlock->getFooterNodeList()
        );
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

        if (count($newBlockList) && $updater = $delegatedNodeUpdater->getUpdater($baseBlock->getItem(), false)) {
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

        return $updater->update($baseItem, $newItem);
    }

    /**
     * @param BlockOrderDelegateInterface $blockOrderDelegate
     * @param array $blockList
     * @return array|\Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block[]
     */
    protected function reorder(BlockOrderDelegateInterface $blockOrderDelegate, array $blockList)
    {
        /** @var Block|null $lastBlock */
        $lastBlock = array_pop($blockList);
        // If last block is not a trailing space, re-append it
        if (!$lastBlock || !$lastBlock->getItem() instanceof UnmanagedNode) {
            $blockList[] = $lastBlock;
            $lastBlock = null;
        }
        $blockList = $blockOrderDelegate->reorder($blockList);

        // Append potential last trailing block if it exist
        if ($lastBlock) {
            $blockList[] = $lastBlock;
        }

        return $blockList;
    }
}

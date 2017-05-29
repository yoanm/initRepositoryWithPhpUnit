<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;

interface BlockOrderDelegateInterface
{
    /**
     * @param Block[] $blockList
     *
     * @return Block[]
     */
    public function reorder(array $blockList);
}

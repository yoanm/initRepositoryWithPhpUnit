<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class Node
{
    /** @var Block[] */
    private $blockList;

    /**
     * @param Block[] $blockList
     */
    public function __construct(array $blockList = [])
    {
        $this->blockList = $blockList;
    }

    /**
     * @return Block[]
     */
    public function getBlockList()
    {
        return $this->blockList;
    }
}

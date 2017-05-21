<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class GroupInclusion extends Node implements ConfigurationItemInterface
{
    /** @var bool */
    private $isExcluded;

    /**
     * @param Block[] $itemList
     * @param bool    $isExcluded
     */
    public function __construct(array $itemList, $isExcluded = true)
    {
        parent::__construct($itemList);
        $this->isExcluded = $isExcluded;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return true === $this->isExcluded;
    }
}

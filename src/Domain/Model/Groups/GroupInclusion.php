<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class GroupInclusion implements ConfigurationItemInterface
{
    /** @var bool */
    private $isExcluded;
    /** @var Group[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param Group[]|ConfigurationItemInterface[] $itemList
     * @param bool                    $isExcluded
     */
    public function __construct($itemList, $isExcluded = true)
    {
        $this->itemList = $itemList;
        $this->isExcluded = $isExcluded;
    }

    /**
     * @return ConfigurationItemInterface[]|Group[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return true === $this->isExcluded;
    }
}

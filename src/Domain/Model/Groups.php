<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class Groups implements ConfigurationItemInterface
{
    /** @var GroupInclusion[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param GroupInclusion[]|ConfigurationItemInterface[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return GroupInclusion[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

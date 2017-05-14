<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class Filter implements ConfigurationItemInterface
{
    /** @var WhiteList[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param WhiteList[]|ConfigurationItemInterface[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return WhiteList[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

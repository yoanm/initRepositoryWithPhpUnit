<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class Filter implements ConfigurationItemInterface
{
    /** @var ConfigurationItemInterface[]|WhiteList[] */
    private $itemList;

    /**
     * @param ConfigurationItemInterface[]|WhiteList[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return ConfigurationItemInterface[]|WhiteList[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

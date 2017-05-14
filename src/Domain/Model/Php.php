<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class Php implements ConfigurationItemInterface
{
    /** @var PhpItem[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param PhpItem[]|ConfigurationItemInterface[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return PhpItem[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

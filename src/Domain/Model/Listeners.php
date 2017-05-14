<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;

class Listeners implements ConfigurationItemInterface
{
    /** @var Listener[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param Listener[]|ConfigurationItemInterface[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return Listener[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

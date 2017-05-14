<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuites implements ConfigurationItemInterface
{
    /** @var TestSuite[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param string                                   $name
     * @param TestSuite[]|ConfigurationItemInterface[] $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return TestSuite[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

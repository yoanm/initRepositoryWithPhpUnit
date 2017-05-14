<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItemInterface;

class TestSuite extends AttributeContainer implements ConfigurationItemInterface
{
    /** @var string */
    private $name;
    /** @var TestSuiteItemInterface[]|ConfigurationItemInterface[] */
    private $itemList;

    /**
     * @param string                       $name
     * @param ConfigurationItemInterface[] $itemList
     * @param Attribute[]                  $attributeList
     */
    public function __construct($name, array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->name = $name;
        $this->itemList = $itemList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return TestSuiteItemInterface[]|ConfigurationItemInterface[]|ConfigurationItemInterface[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

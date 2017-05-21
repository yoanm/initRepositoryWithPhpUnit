<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class TestSuite extends AttributeContainer implements ConfigurationItemInterface
{
    /** @var string */
    private $name;

    /**
     * @param string                       $name
     * @param ConfigurationItemInterface[] $itemList
     * @param Attribute[]                  $attributeList
     */
    public function __construct($name, array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList, $itemList);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

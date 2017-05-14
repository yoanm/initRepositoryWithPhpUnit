<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Php;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;

class PhpItem extends AttributeContainer
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;

    /**
     * @param string      $name
     * @param string      $value
     * @param Attribute[] $attributeList
     */
    public function __construct($name, $value = null, array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

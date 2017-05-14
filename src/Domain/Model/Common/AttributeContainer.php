<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class AttributeContainer implements ConfigurationItemInterface
{
    /** @var Attribute[] */
    private $attributeList = [];

    /**
     * @param Attribute[] $attributeList
     */
    public function __construct(array $attributeList = [])
    {
        $this->attributeList = $attributeList;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributeList()
    {
        return $this->attributeList;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class AttributeContainer extends Node
{
    /** @var Attribute[] */
    private $attributeList = [];

    /**
     * @param Attribute[] $attributeList
     * @param Block[]     $itemList
     */
    public function __construct(array $attributeList = [], array $itemList = [])
    {
        parent::__construct($itemList);
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

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class AttributeNormalizer
{
    /**
     * @param Attribute    $attribute
     * @param \DOMDocument $document
     *
     * @return \DomAttr
     */
    public function normalize(Attribute $attribute, \DOMDocument $document)
    {
        $attributeNode = $document->createAttribute($attribute->getName());
        $attributeNode->value = $attribute->getValue();

        return $attributeNode;
    }

    /**
     * @param \DomAttr $attribute
     *
     * @return Attribute
     */
    public function denormalize(\DomAttr $attribute)
    {
        return new Attribute($attribute->nodeName, $attribute->nodeValue);
    }
}

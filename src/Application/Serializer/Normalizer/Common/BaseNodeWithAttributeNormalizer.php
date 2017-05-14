<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class BaseNodeWithAttributeNormalizer extends BaseNodeNormalizer
{
    /** @var AttributeNormalizer */
    private $attributeNormalizer;

    /**
     * @param AttributeNormalizer                           $attributeNormalizer
     * @param NormalizerInterface[]|DenormalizerInterface[] $delegateList
     */
    public function __construct(AttributeNormalizer $attributeNormalizer, array $delegateList = [])
    {
        parent::__construct($delegateList);
        $this->attributeNormalizer = $attributeNormalizer;
    }

    /**
     * @param \DomNode     $node
     * @param Attribute[]  $attributeList
     * @param \DOMDocument $document
     */
    protected function appendAttributes(\DomNode $node, array $attributeList, \DOMDocument $document)
    {
        foreach ($attributeList as $attribute) {
            $node->appendChild(
                $this->attributeNormalizer->normalize($attribute, $document)
            );
        }
    }

    /**
     * @param \DOMNode $node
     *
     * @return Attribute[]
     */
    protected function extractAttributes(\DOMNode $node)
    {
        $attributeList = [];
        $itemCount = $node->attributes->length;
        for ($counter = 0 ; $counter < $itemCount ; $counter++) {
            $rawAttribute = $node->attributes->item($counter);
            $attributeList[] = $this->attributeNormalizer->denormalize($rawAttribute);
        }

        return $attributeList;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class NodeWithAttributeNormalizer extends NodeNormalizer
{
    /** @var AttributeNormalizer */
    private $attributeNormalizer;

    /**
     * @param AttributeNormalizer                           $attributeNormalizer
     * @param NodeNormalizerHelper                          $nodeNormalizerHelper
     * @param NormalizerInterface[]|DenormalizerInterface[] $delegateList
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        array $delegateList = []
    ) {
        parent::__construct($nodeNormalizerHelper, $delegateList);
        $this->attributeNormalizer = $attributeNormalizer;
    }

    /**
     * @param \DOMNode $domNode
     *
     * @return Attribute[]
     */
    protected function extractAttributes(\DOMNode $domNode)
    {
        $attributeList = [];
        $itemCount = $domNode->attributes->length;
        for ($counter = 0; $counter < $itemCount; $counter++) {
            $rawAttribute = $domNode->attributes->item($counter);
            $attributeList[] = $this->attributeNormalizer->denormalize($rawAttribute);
        }

        return $attributeList;
    }
}

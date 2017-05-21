<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Php;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpItemNormalizer extends NodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param PhpItem $phpItem
     *
     * @return NormalizedNode
     */
    public function normalize($phpItem)
    {
        $value = trim($phpItem->getValue());
        $value = strlen($value) ? $value : null;

        return new NormalizedNode(
            $phpItem->getAttributeList(),
            [],
            $phpItem->getName(),
            $value
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return PhpItem
     */
    public function denormalize(\DOMNode $node)
    {
        return new PhpItem(
            $node->nodeName,
            $node->nodeValue,
            $this->extractAttributes($node)
        );
    }


    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof PhpItem;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return $node instanceof \DOMElement;
    }
}

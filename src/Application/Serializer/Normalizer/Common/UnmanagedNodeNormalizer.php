<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class UnmanagedNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param UnmanagedNode $unmanagedNode
     *
     * @return NormalizedNode
     */
    public function normalize($unmanagedNode)
    {
        return new NormalizedNode(
            [],
            [],
            null,
            $unmanagedNode->getValue()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(\DOMNode $node)
    {
        return new UnmanagedNode($node);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof UnmanagedNode;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return $node instanceof \DOMComment
            || $node instanceof \DOMText;
    }
}

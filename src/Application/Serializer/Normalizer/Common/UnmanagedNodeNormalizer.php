<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class UnmanagedNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param UnmanagedNode $unmanagedNode
     *
     * @return mixed
     */
    public function normalize($unmanagedNode, \DOMDocument $document)
    {
        $node = $unmanagedNode->getValue();
        //$node = $document->createTextNode('**'.$unmanagedNode->getValue()->nodeName);
        return $document->importNode($node, true);
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

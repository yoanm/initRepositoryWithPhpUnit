<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class ExcludedWhiteListNormalizer extends NodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const NODE_NAME = 'exclude';

    /**
     * @param NodeNormalizerHelper     $nodeNormalizerHelper
     * @param AttributeNormalizer      $attributeNormalizer
     * @param WhiteListEntryNormalizer $whiteListEntryNormalizer
     * @param UnmanagedNodeNormalizer  $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        WhiteListEntryNormalizer $whiteListEntryNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            $attributeNormalizer,
            [
                $whiteListEntryNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param ExcludedWhiteList $whiteList
     * @param \DOMDocument      $document
     *
     * @return \DOMElement
     */
    public function normalize($whiteList, \DOMDocument $document)
    {
        $domNode = $this->createElementNode(
            $document,
            self::NODE_NAME
        );


        $this->getHelper()->normalizeAndAppendBlockList($domNode, $whiteList, $document, $this);

        return $domNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return WhiteList
     */
    public function denormalize(\DOMNode $node)
    {
        return new ExcludedWhiteList(
            $this->extractAttributes($node),
            $this->getHelper()->denormalizeChildNode($node, $this)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof ExcludedWhiteList;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

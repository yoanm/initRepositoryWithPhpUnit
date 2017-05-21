<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter\WhiteListNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

class FilterNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'filter';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param WhiteListNormalizer     $whiteListNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        WhiteListNormalizer $whiteListNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $whiteListNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Filter       $filter
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($filter, \DOMDocument $document)
    {
        $domNode = $this->createElementNode($document, self::NODE_NAME);

        $this->getHelper()->normalizeAndAppendBlockList($domNode, $filter, $document, $this);

        return $domNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Filter
     */
    public function denormalize(\DOMNode $node)
    {
        return new Filter($this->getHelper()->denormalizeChildNode($node, $this));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Filter;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

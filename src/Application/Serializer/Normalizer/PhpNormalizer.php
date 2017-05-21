<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Php\PhpItemNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'php';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param PhpItemNormalizer       $phpItemNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        PhpItemNormalizer $phpItemNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $phpItemNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Php $php
     *
     * @return NormalizedNode
     */
    public function normalize($php)
    {
        return new NormalizedNode(
            [],
            $this->getHelper()->normalizeBlockList($php, $this),
            self::NODE_NAME
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Php
     */
    public function denormalize(\DOMNode $node)
    {
        return new Php($this->getHelper()->denormalizeChildNode($node, $this));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Php;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

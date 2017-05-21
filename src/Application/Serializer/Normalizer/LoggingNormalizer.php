<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Logging\LogNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;

class LoggingNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'logging';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param LogNormalizer           $logNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        LogNormalizer $logNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $logNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Logging $logging
     *
     * @return NormalizedNode
     */
    public function normalize($logging)
    {
        return new NormalizedNode(
            [],
            $this->getHelper()->normalizeBlockList($logging, $this),
            self::NODE_NAME
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Logging
     */
    public function denormalize(\DOMNode $node)
    {
        return new Logging($this->getHelper()->denormalizeChildNode($node, $this));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Logging;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

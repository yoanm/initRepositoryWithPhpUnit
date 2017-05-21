<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Listeners\ListenerNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;

class ListenersNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'listeners';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param ListenerNormalizer      $listenerNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        ListenerNormalizer $listenerNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $listenerNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Listeners $listeners
     *
     * @return NormalizedNode
     */
    public function normalize($listeners)
    {
        return new NormalizedNode(
            [],
            $this->getHelper()->normalizeBlockList($listeners, $this),
            self::NODE_NAME
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Listeners
     */
    public function denormalize(\DOMNode $node)
    {
        return new Listeners($this->getHelper()->denormalizeChildNode($node, $this));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

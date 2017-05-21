<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Logging;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;

class LogNormalizer extends NodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'log';

    /**
     * @param Log $logItem
     *
     * @return NormalizedNode
     */
    public function normalize($logItem)
    {
        return new NormalizedNode(
            $logItem->getAttributeList(),
            [],
            self::NODE_NAME
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Log
     */
    public function denormalize(\DOMNode $node)
    {
        return new Log($this->extractAttributes($node));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Log;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

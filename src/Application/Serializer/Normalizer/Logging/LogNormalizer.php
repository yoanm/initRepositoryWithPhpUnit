<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Logging;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;

class LogNormalizer extends BaseNodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'log';

    /**
     * @param Log      $logItem
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($logItem, \DOMDocument $document)
    {
        $element = $this->createElementNode($document, self::NODE_NAME, null);

        $this->appendAttributes($element, $logItem->getAttributeList(), $document);

        return $element;
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

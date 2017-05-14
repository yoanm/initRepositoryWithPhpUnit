<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Php;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpItemNormalizer extends BaseNodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param PhpItem      $phpItem
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($phpItem, \DOMDocument $document)
    {
        $value = trim($phpItem->getValue());
        $value = strlen($value) ? $value : null;

        $element = $this->createElementNode(
            $document,
            $phpItem->getName(),
            $value
        );

        $this->appendAttributes($element, $phpItem->getAttributeList(), $document);

        return $element;
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

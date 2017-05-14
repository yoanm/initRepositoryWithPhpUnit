<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Logging\LogNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;

class LoggingNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'logging';

    public function __construct(
        LogNormalizer $logNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $logNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param Logging      $logging
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($logging, \DOMDocument $document)
    {
        $logItemListNode = $this->createElementNode($document, self::NODE_NAME);
        foreach ($logging->getItemList() as $item) {
            $logItemListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $logItemListNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Logging
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new Logging($itemList);
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

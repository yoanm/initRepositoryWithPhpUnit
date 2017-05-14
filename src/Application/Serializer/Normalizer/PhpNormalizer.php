<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Php\PhpItemNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'php';

    public function __construct(
        PhpItemNormalizer $phpItemNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $phpItemNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param Php          $php
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($php, \DOMDocument $document)
    {
        $phpItemListNode = $this->createElementNode($document, self::NODE_NAME);
        foreach ($php->getItemList() as $item) {
            $phpItemListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $phpItemListNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return PhpItem[]
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new Php($itemList);
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

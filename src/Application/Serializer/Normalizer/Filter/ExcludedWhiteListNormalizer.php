<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\FilesystemItemNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;

class ExcludedWhiteListNormalizer extends BaseNodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const NODE_NAME = 'exclude';

    public function __construct(
        AttributeNormalizer $attributeNormalizer,
        WhiteListEntryNormalizer $whiteListEntryNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
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
        $excludedNode = $this->createElementNode(
            $document,
            self::NODE_NAME
        );


        foreach ($whiteList->getItemList() as $item) {
            $excludedNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $excludedNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return WhiteList
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new ExcludedWhiteList($itemList, $this->extractAttributes($node));
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

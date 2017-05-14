<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class WhiteListNormalizer extends BaseNodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'whitelist';
    const EXCLUDED_ITEM_LIST_NODE_NAME = 'exclude';

    public function __construct(
        AttributeNormalizer $attributeNormalizer,
        WhiteListEntryNormalizer $whiteListEntryNormalizer,
        ExcludedWhiteListNormalizer $excludedWhiteListNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $attributeNormalizer,
            [
                $whiteListEntryNormalizer,
                $excludedWhiteListNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param WhiteList    $whiteList
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($whiteList, \DOMDocument $document)
    {
        $whiteListNode = $this->createElementNode($document, self::NODE_NAME);
        $this->appendAttributes($whiteListNode, $whiteList->getAttributeList(), $document);

        foreach ($whiteList->getItemList() as $item) {
            $whiteListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $whiteListNode;
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

        return new WhiteList(
            $itemList,
            $this->extractAttributes($node)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf WhiteList;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

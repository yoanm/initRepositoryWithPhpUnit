<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;

class WhiteListNormalizer extends NodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'whitelist';
    const EXCLUDED_ITEM_LIST_NODE_NAME = 'exclude';

    /**
     * @param NodeNormalizerHelper        $nodeNormalizerHelper
     * @param AttributeNormalizer         $attributeNormalizer
     * @param WhiteListEntryNormalizer    $whiteListEntryNormalizer
     * @param ExcludedWhiteListNormalizer $excludedWhiteListNormalizer
     * @param UnmanagedNodeNormalizer     $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        WhiteListEntryNormalizer $whiteListEntryNormalizer,
        ExcludedWhiteListNormalizer $excludedWhiteListNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            $attributeNormalizer,
            [
                $whiteListEntryNormalizer,
                $excludedWhiteListNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param WhiteList $whiteList
     *
     * @return NormalizedNode
     */
    public function normalize($whiteList)
    {
        return new NormalizedNode(
            $whiteList->getAttributeList(),
            $this->getHelper()->normalizeBlockList($whiteList, $this),
            self::NODE_NAME
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return WhiteList
     */
    public function denormalize(\DOMNode $node)
    {
        return new WhiteList(
            $this->extractAttributes($node),
            $this->getHelper()->denormalizeChildNode($node, $this)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof WhiteList;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

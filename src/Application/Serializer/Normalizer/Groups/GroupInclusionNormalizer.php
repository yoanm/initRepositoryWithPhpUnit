<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class GroupInclusionNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const INCLUDED_NODE_NAME = 'include';
    const EXCLUDED_NODE_NAME = 'exclude';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param GroupNormalizer         $groupNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        GroupNormalizer $groupNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $groupNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param GroupInclusion $groupInclusion
     * @param \DOMDocument   $document
     *
     * @return \DOMElement
     */
    public function normalize($groupInclusion, \DOMDocument $document)
    {
        $domNode = $this->createElementNode(
            $document,
            $groupInclusion->isExcluded() ? self::EXCLUDED_NODE_NAME : self::INCLUDED_NODE_NAME
        );

        $this->getHelper()->normalizeAndAppendBlockList($domNode, $groupInclusion, $document, $this);

        return $domNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return GroupInclusion
     */
    public function denormalize(\DOMNode $node)
    {
        return new GroupInclusion(
            $this->getHelper()->denormalizeChildNode($node, $this),
            self::EXCLUDED_NODE_NAME === $node->nodeName
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof GroupInclusion;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::EXCLUDED_NODE_NAME === $node->nodeName
            || self::INCLUDED_NODE_NAME === $node->nodeName;
    }
}

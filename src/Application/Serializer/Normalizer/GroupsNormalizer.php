<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups\GroupInclusionNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

class GroupsNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'groups';

    /**
     * @param NodeNormalizerHelper     $nodeNormalizerHelper
     * @param GroupInclusionNormalizer $groupInclusionNormalizer
     * @param UnmanagedNodeNormalizer  $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        GroupInclusionNormalizer $groupInclusionNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $groupInclusionNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Groups       $groups
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($groups, \DOMDocument $document)
    {
        $domNode = $this->createElementNode($document, self::NODE_NAME);

        $this->getHelper()->normalizeAndAppendBlockList($domNode, $groups, $document, $this);

        return $domNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Groups
     */
    public function denormalize(\DOMNode $node)
    {
        return new Groups($this->getHelper()->denormalizeChildNode($node, $this));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Groups;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

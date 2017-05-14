<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class GroupInclusionNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const INCLUDED_NODE_NAME = 'include';
    const EXCLUDED_NODE_NAME = 'exclude';

    public function __construct(
        GroupNormalizer $groupNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $groupNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param GroupInclusion $groupInclusion
     * @param \DOMDocument   $document
     *
     * @return \DOMElement
     */
    public function normalize($groupInclusion, \DOMDocument $document)
    {
        $groupListNode = $this->createElementNode(
            $document,
            $groupInclusion->isExcluded() ? self::EXCLUDED_NODE_NAME : self::INCLUDED_NODE_NAME
        );

        foreach ($groupInclusion->getItemList() as $item) {
            $groupListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $groupListNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return GroupInclusion
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new GroupInclusion(
            $itemList,
            self::EXCLUDED_NODE_NAME === $node->nodeName
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf GroupInclusion;
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

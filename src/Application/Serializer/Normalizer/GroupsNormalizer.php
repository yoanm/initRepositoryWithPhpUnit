<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DelegatedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups\GroupInclusionNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

class GroupsNormalizer extends DelegatedNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'groups';

    public function __construct(
        GroupInclusionNormalizer $groupInclusionNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $groupInclusionNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param Groups       $groups
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($groups, \DOMDocument $document)
    {
        $groupListNode = $this->createElementNode($document, self::NODE_NAME);

        foreach ($groups->getItemList() as $item) {
            $groupListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $groupListNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Groups
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new Groups($itemList);
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

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;

class GroupNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'group';

    /**
     * @param Group        $group
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($group, \DOMDocument $document)
    {
        return $this->createElementNode(
            $document,
            self::NODE_NAME,
            $group->getValue()
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Group
     */
    public function denormalize(\DOMNode $node)
    {
        return new Group($node->nodeValue);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Group;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Listeners;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;

class ListenerNormalizer extends NodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'listener';
    const CLASS_ATTRIBUTE = 'class';
    const FILE_ATTRIBUTE = 'file';

    /** @var UnmanagedNodeNormalizer */
    private $unmanagedNodeNormalizer;

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param AttributeNormalizer     $attributeNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct($nodeNormalizerHelper, $attributeNormalizer);
        $this->unmanagedNodeNormalizer = $unmanagedNodeNormalizer;
    }

    /**
     * @param Listener     $listener
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($listener, \DOMDocument $document)
    {
        $attributeList = [];
        if (null !== $listener->getClass()) {
            $attributeList[] = new Attribute(self::CLASS_ATTRIBUTE, $listener->getClass());
        }
        if (null !== $listener->getFile()) {
            $attributeList[] = new Attribute(self::FILE_ATTRIBUTE, $listener->getFile());
        }

        $domNode = $this->createElementNode($document, self::NODE_NAME);

        $this->appendAttributes($domNode, $attributeList, $document);

        foreach ($listener->getBlockList() as $item) {
            $domNode->appendChild(
                $this->unmanagedNodeNormalizer->normalize($item->getItem(), $document)
            );
        }

        return $domNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Listener
     */
    public function denormalize(\DOMNode $node)
    {
        $attributeList = $this->extractAttributes($node);
        $class = $file = null;
        foreach ($attributeList as $attribute) {
            if (self::CLASS_ATTRIBUTE === $attribute->getName()) {
                $class = $attribute->getValue();
            } elseif (self::FILE_ATTRIBUTE === $attribute->getName()) {
                $file = $attribute->getValue();
            }
        }

        $itemList = [];
        foreach ($this->getHelper()->extractChildNodeList($node) as $childNode) {
            $itemList[] = new Block($this->unmanagedNodeNormalizer->denormalize($childNode));
        }

        return new Listener($class, $file, $itemList);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Listener;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;

class FilesystemItemNormalizer extends BaseNodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const FILE_NODE_NAME = 'file';
    const DIRECTORY_NODE_NAME = 'directory';

    /**
     * @param FilesystemItem $item
     * @param \DOMDocument   $document
     *
     * @return \DOMElement
     */
    public function normalize($item, \DOMDocument $document)
    {
        $nodeName = self::FILE_NODE_NAME;
        if ($item->getType() == FilesystemItem::TYPE_DIRECTORY) {
            $nodeName = self::DIRECTORY_NODE_NAME;
        }
        $itemNode = $this->createElementNode($document, $nodeName, $item->getValue());

        $this->appendAttributes($itemNode, $item->getAttributeList(), $document);

        return $itemNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return FilesystemItem
     */
    public function denormalize(\DOMNode $node)
    {
        $type = FilesystemItem::TYPE_FILE;
        if (self::DIRECTORY_NODE_NAME === $node->nodeName) {
            $type = FilesystemItem::TYPE_DIRECTORY;
        }

        return new FilesystemItem($type, $node->nodeValue, $this->extractAttributes($node));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof FilesystemItem;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::FILE_NODE_NAME === $node->nodeName
            || self::DIRECTORY_NODE_NAME === $node->nodeName
        ;
    }
}

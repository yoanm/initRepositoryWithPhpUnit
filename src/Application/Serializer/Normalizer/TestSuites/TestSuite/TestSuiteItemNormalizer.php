<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\FilesystemItemNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;

class TestSuiteItemNormalizer extends BaseNodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const EXCLUDED_NODE_NAME = 'exclude';

    /** @var FilesystemItemNormalizer */
    private $filesystemItemNormalizer;

    public function __construct(
        AttributeNormalizer $attributeNormalizer,
        FilesystemItemNormalizer $filesystemItemNormalizer
    ) {
        parent::__construct($attributeNormalizer);
        $this->filesystemItemNormalizer = $filesystemItemNormalizer;
    }

    /**
     * @param ExcludedTestSuiteItem|TestSuiteItem $item
     * @param \DOMDocument                        $document
     *
     * @return \DomElement
     */
    public function normalize($item, \DOMDocument $document)
    {
        if ($item instanceof ExcludedTestSuiteItem) {
            $itemNode = $this->createElementNode(
                $document,
                self::EXCLUDED_NODE_NAME,
                $item->getValue()
            );
        } else {
            $itemNode = $this->filesystemItemNormalizer->normalize($item, $document);
        }

        return $itemNode;
    }

    /**
     * @param \DomNode $itemNode
     *
     * @return ConfigurationItemInterface
     */
    public function denormalize(\DomNode $itemNode)
    {
        if (self::EXCLUDED_NODE_NAME === $itemNode->nodeName) {
            $item = new ExcludedTestSuiteItem($itemNode->nodeValue);
        } else {
            $fsItem = $this->filesystemItemNormalizer->denormalize($itemNode);
            $item = new TestSuiteItem(
                $fsItem->getType(),
                $fsItem->getValue(),
                $this->extractAttributes($itemNode)
            );
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof TestSuiteItem
            || $item instanceof ExcludedTestSuiteItem
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::EXCLUDED_NODE_NAME === $node->nodeName
            || $this->filesystemItemNormalizer->supportsDenormalization($node)
        ;
    }
}

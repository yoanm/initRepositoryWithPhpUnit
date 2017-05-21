<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\FilesystemItemNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;

class TestSuiteItemNormalizer extends NodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    /** @var FilesystemItemNormalizer */
    private $filesystemItemNormalizer;

    /**
     * @param NodeNormalizerHelper     $nodeNormalizerHelper
     * @param AttributeNormalizer      $attributeNormalizer
     * @param FilesystemItemNormalizer $filesystemItemNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        FilesystemItemNormalizer $filesystemItemNormalizer
    ) {
        parent::__construct($nodeNormalizerHelper, $attributeNormalizer);
        $this->filesystemItemNormalizer = $filesystemItemNormalizer;
    }

    /**
     * @param TestSuiteItem $item
     *
     * @return NormalizedNode
     */
    public function normalize($item)
    {
        return $this->filesystemItemNormalizer->normalize($item);
    }

    /**
     * @param \DomNode $itemNode
     *
     * @return ConfigurationItemInterface
     */
    public function denormalize(\DomNode $itemNode)
    {
        $fsItem = $this->filesystemItemNormalizer->denormalize($itemNode);

        return new TestSuiteItem(
            $fsItem->getType(),
            $fsItem->getValue(),
            $this->extractAttributes($itemNode)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof TestSuiteItem;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return $this->filesystemItemNormalizer->supportsDenormalization($node);
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;

class ExcludedTestSuiteItemNormalizer extends NodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const NODE_NAME = 'exclude';

    /**
     * @param ExcludedTestSuiteItem $item
     *
     * @return NormalizedNode
     */
    public function normalize($item)
    {
        return new NormalizedNode(
            [],
            [],
            self::NODE_NAME,
            $item->getValue()
        );
    }

    /**
     * @param \DomNode $itemNode
     *
     * @return ConfigurationItemInterface
     */
    public function denormalize(\DomNode $itemNode)
    {
        return new ExcludedTestSuiteItem($itemNode->nodeValue);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof ExcludedTestSuiteItem;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

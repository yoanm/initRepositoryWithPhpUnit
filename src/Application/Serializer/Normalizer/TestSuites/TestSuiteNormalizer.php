<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite\ExcludedTestSuiteItemNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite\TestSuiteItemNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuiteNormalizer extends NodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'testsuite';

    const NAME_ATTRIBUTE = 'name';

    /**
     * @param NodeNormalizerHelper            $nodeNormalizerHelper
     * @param AttributeNormalizer             $attributeNormalizer
     * @param TestSuiteItemNormalizer         $testSuiteItemNormalizer
     * @param ExcludedTestSuiteItemNormalizer $excludedTestSuiteItemNormalizer
     * @param UnmanagedNodeNormalizer         $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        TestSuiteItemNormalizer $testSuiteItemNormalizer,
        ExcludedTestSuiteItemNormalizer $excludedTestSuiteItemNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            $attributeNormalizer,
            [
                $excludedTestSuiteItemNormalizer,
                $testSuiteItemNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param TestSuite    $testSuite
     * @param \DOMDocument $document
     *
     * @return \DomElement
     */
    public function normalize($testSuite, \DOMDocument $document)
    {
        $domNode = $this->createElementNode($document, self::NODE_NAME);

        // Append attributes
        $attributeList = $testSuite->getAttributeList();
        $attributeList[] = new Attribute(self::NAME_ATTRIBUTE, $testSuite->getName());

        $this->appendAttributes($domNode, $attributeList, $document);

        $this->getHelper()->normalizeAndAppendBlockList($domNode, $testSuite, $document, $this);

        return $domNode;
    }

    /**
     * @param \DomNode $node
     *
     * @return TestSuite
     */
    public function denormalize(\DomNode $node)
    {
        $attributeList = $this->extractAttributes($node);
        $testSuiteName = null;

        foreach ($attributeList as $key => $attribute) {
            if (self::NAME_ATTRIBUTE === $attribute->getName()) {
                $testSuiteName = $attribute->getValue();
                unset($attributeList[$key]);
            }
        }

        return new TestSuite(
            $testSuiteName,
            $this->getHelper()->denormalizeChildNode($node, $this),
            $attributeList
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof TestSuite;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

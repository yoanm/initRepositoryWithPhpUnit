<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite\TestSuiteItemNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuiteNormalizer extends BaseNodeWithAttributeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'testsuite';

    const NAME_ATTRIBUTE = 'name';

    public function __construct(
        AttributeNormalizer $attributeNormalizer,
        TestSuiteItemNormalizer $testSuiteItemNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $attributeNormalizer,
            [
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
        $element = $this->createElementNode($document, self::NODE_NAME);

        // Append attributes
        $attributeList = $testSuite->getAttributeList();
        $attributeList[] = new Attribute(self::NAME_ATTRIBUTE, $testSuite->getName());
        $this->appendAttributes($element, $attributeList, $document);

        // Append content
        foreach ($testSuite->getItemList() as $item) {
            $element->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $element;
    }

    /**
     * @param \DomNode $node
     *
     * @return TestSuite
     */
    public function denormalize(\DomNode $node)
    {
        $itemList = [];
        $attributeList = $this->extractAttributes($node);
        $testSuiteName = null;

        foreach ($attributeList as $key => $attribute) {
            if (self::NAME_ATTRIBUTE === $attribute->getName()) {
                $testSuiteName = $attribute->getValue();
                unset($attributeList[$key]);
            }
        }

        foreach ($this->extractChildNodeList($node) as $nodeItem) {
            $itemList[] = $this->getDenormalizer($nodeItem)->denormalize($nodeItem);
        }

        return new TestSuite(
            $testSuiteName,
            $itemList,
            $attributeList
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf TestSuite;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

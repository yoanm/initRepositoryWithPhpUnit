<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuiteNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuitesNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'testsuites';

    public function __construct(
        TestSuiteNormalizer $testSuiteNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $testSuiteNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param TestSuites   $testSuites
     * @param \DOMDocument $document
     *
     * @return \DomElement
     */
    public function normalize($testSuites, \DOMDocument $document)
    {
        $itemListNode = $this->createElementNode($document, self::NODE_NAME);
        foreach ($testSuites->getItemList() as $item) {
            $itemListNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $itemListNode;
    }

    /**
     * @param \DomNode $node
     *
     * @return TestSuite[]
     */
    public function denormalize(\DomNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new TestSuites($itemList);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf TestSuites;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

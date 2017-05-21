<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuiteNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuitesNormalizer extends NodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'testsuites';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param TestSuiteNormalizer     $testSuiteNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        TestSuiteNormalizer $testSuiteNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $testSuiteNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param TestSuites   $testSuites
     *
     * @return \DomElement
     */
    public function normalize($testSuites)
    {
        return new NormalizedNode(
            [],
            $this->getHelper()->normalizeBlockList($testSuites, $this),
            self::NODE_NAME
        );
    }

    /**
     * @param \DomNode $node
     *
     * @return TestSuites
     */
    public function denormalize(\DomNode $node)
    {
        return new TestSuites($this->denormalizeChildNode($node));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof TestSuites;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

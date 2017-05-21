<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\AttributeNS;
use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

class ConfigurationNormalizer extends NodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    const NODE_NAME = 'phpunit';

    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param AttributeNormalizer     $attributeNormalizer
     * @param TestSuitesNormalizer    $testSuiteListNormalizer
     * @param GroupsNormalizer        $groupsNormalizer
     * @param FilterNormalizer        $filterNormalizer
     * @param LoggingNormalizer       $loggingNormalizer
     * @param ListenersNormalizer     $listenersNormalizer
     * @param PhpNormalizer           $phpNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        TestSuitesNormalizer $testSuiteListNormalizer,
        GroupsNormalizer $groupsNormalizer,
        FilterNormalizer $filterNormalizer,
        LoggingNormalizer $loggingNormalizer,
        ListenersNormalizer $listenersNormalizer,
        PhpNormalizer $phpNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            $attributeNormalizer,
            [
                $testSuiteListNormalizer,
                $groupsNormalizer,
                $filterNormalizer,
                $loggingNormalizer,
                $listenersNormalizer,
                $phpNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    /**
     * @param Configuration $configuration
     *
     * @return NormalizedNode
     */
    public function normalize($configuration)
    {
        list ($attributeList, $attributeNSList) = $this->splitAttributeList($configuration);

        return new NormalizedNode(
            $attributeList,
            $this->getHelper()->normalizeBlockList($configuration, $this),
            self::NODE_NAME,
            null,
            $attributeNSList
        );
    }

    /**
     * @param \DOMNode $node
     *
     * @return Configuration
     */
    public function denormalize(\DOMNode $node)
    {
        return new Configuration(
            $this->extractAttributes($node),
            $this->denormalizeChildNode($node)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceof Configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return ConfigurationNormalizer::NODE_NAME === $node->nodeName;
    }

    /**
     * @param Configuration $configuration
     *
     * @return array
     */
    private function splitAttributeList(Configuration $configuration)
    {
        $xmlnsXsiAttrValue = 'http://www.w3.org/2001/XMLSchema-instance';
        $noNamespaceLocationAttrValue = 'http://schema.phpunit.de/4.5/phpunit.xsd';
        $attributeList = $configuration->getAttributeList();
        foreach ($attributeList as $key => $attribute) {
            if ('xmlns:xsi' === $attribute->getName()) {
                $xmlnsXsiAttrValue = $attribute->getValue();
                unset($attributeList[$key]);
            } elseif ('xsi:noNamespaceSchemaLocation' === $attribute->getName()) {
                $noNamespaceLocationAttrValue = $attribute->getValue();
                unset($attributeList[$key]);
            }
        }

        $attributeNSList = [
            new AttributeNS(
                'http://www.w3.org/2000/xmlns/',
                'xmlns:xsi',
                $xmlnsXsiAttrValue
            ),
            new AttributeNS(
                $xmlnsXsiAttrValue,
                'xsi:noNamespaceSchemaLocation',
                $noNamespaceLocationAttrValue
            )
        ];


        return [$attributeList, $attributeNSList];
    }
}

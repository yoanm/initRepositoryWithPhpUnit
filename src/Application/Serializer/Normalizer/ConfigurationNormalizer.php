<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
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

    public function __construct(
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
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($configuration, \DOMDocument $document)
    {
        $node = $document->createElement(self::NODE_NAME);
        $document->appendChild($node);

        $this->appendConfigAttributeList($document, $configuration, $node);

        foreach ($configuration->getItemList() as $item) {
            $node->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $node;
    }

    /**
     * @param \DOMNode $document
     *
     * @return Configuration
     */
    public function denormalize(\DOMNode $document)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($document) as $node) {
            $itemList[] = $this->getDenormalizer($node)->denormalize($node);
        }

        return new Configuration(
            $itemList,
            $this->extractAttributes($document)
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
     * @param \DOMDocument  $document
     * @param Configuration $configuration
     * @param \DOMElement   $node
     */
    private function appendConfigAttributeList(\DOMDocument $document, Configuration $configuration, \DOMElement $node)
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

        $node->setAttributeNS(
            'http://www.w3.org/2000/xmlns/',
            'xmlns:xsi',
            $xmlnsXsiAttrValue
        );
        $node->setAttributeNS(
            $xmlnsXsiAttrValue,
            'xsi:noNamespaceSchemaLocation',
            $noNamespaceLocationAttrValue
        );


        $this->appendAttributes($node, $attributeList, $document);
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileNormalizer extends NodeNormalizer
{
    /**
     * @param NodeNormalizerHelper    $nodeNormalizerHelper
     * @param ConfigurationNormalizer $configurationNormalizer
     * @param UnmanagedNodeNormalizer $unmanagedNodeNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        ConfigurationNormalizer $configurationNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            [
                $configurationNormalizer,
                $unmanagedNodeNormalizer,
            ]
        );
    }

    public function normalize(ConfigurationFile $configurationFile)
    {
        $document = new \DOMDocument($configurationFile->getVersion(), $configurationFile->getEncoding());

        $this->appendNormalizedNodeList(
            $document,
            $document,
            $this->getHelper()->normalizeBlockList($configurationFile, $this)
        );

        return $document;
    }

    /**
     * @param \DOMNode $document
     *
     * @return ConfigurationFile
     * @throws \UnexpectedValueException if given object is not a right instance
     */
    public function denormalize(\DOMNode $document)
    {
        if (!$document instanceof \DOMDocument) {
            throw new \UnexpectedValueException(sprintf(
                'Document must be an instance of %s',
                \DOMDocument::class
            ));
        }

        return new ConfigurationFile(
            $document->xmlVersion,
            $document->encoding,
            $this->denormalizeChildNode($document)
        );
    }

    /**
     * @param \DOMNode         $rootNode
     * @param NormalizedNode[] $normalizedNodeList
     */
    private function appendNormalizedNodeList(\DOMDocument $rootDocument, \DOMNode $rootNode, array $normalizedNodeList)
    {
        foreach ($normalizedNodeList as $normalizedNode) {
            if ($normalizedNode->getNodeContent() instanceof \DOMNode) {
                $rootNode->appendChild(
                    $rootDocument->importNode($normalizedNode->getNodeContent(), true)
                );
            } else {
                $node = $rootDocument->createElement(
                    $normalizedNode->getNodeName(),
                    $normalizedNode->getNodeContent()
                );

                foreach ($normalizedNode->getNodeAttributeNSList() as $attributeNS) {
                    $node->setAttributeNS(
                        $attributeNS->getNamespaceURI(),
                        $attributeNS->getQualifiedName(),
                        $attributeNS->getValue()
                    );
                }

                foreach ($normalizedNode->getNodeAttributeList() as $attribute) {
                    $node->setAttribute($attribute->getName(), $attribute->getValue());
                }

                $this->appendNormalizedNodeList($rootDocument, $node, $normalizedNode->getNodeChildList());

                $rootNode->appendChild($node);
            }
        }
    }
}

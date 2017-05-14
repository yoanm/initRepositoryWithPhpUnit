<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileNormalizer extends BaseNodeNormalizer
{
    public function __construct(
        ConfigurationNormalizer $configurationNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $configurationNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    public function normalize(ConfigurationFile $configurationFile)
    {
        $document = new \DOMDocument($configurationFile->getVersion(), $configurationFile->getEncoding());

        foreach ($configurationFile->getNodeList() as $node) {
            $document->appendChild(
                $this->getNormalizer($node)->normalize($node, $document)
            );
        }

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
        $nodeList = [];
        foreach ($this->extractChildNodeList($document) as $itemNode) {
            $nodeList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new ConfigurationFile(
            $document->xmlVersion,
            $document->encoding,
            $nodeList
        );
    }
}

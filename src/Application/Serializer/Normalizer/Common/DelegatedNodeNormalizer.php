<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

class DelegatedNodeNormalizer
{
    /** @var NormalizerInterface[] */
    private $normalizerDelegateList = [];
    /** @var DenormalizerInterface[] */
    private $denormalizerDelegateList = [];

    /**
     * @param NormalizerInterface[]|DenormalizerInterface[] $delegateList
     */
    public function __construct(array $delegateList = [])
    {
        foreach ($delegateList as $delegate) {
            if ($delegate instanceof NormalizerInterface) {
                $this->normalizerDelegateList[] = $delegate;
            }
            if ($delegate instanceof DenormalizerInterface) {
                $this->denormalizerDelegateList[] = $delegate;
            }
        }
    }

    /**
     * @param mixed $item
     *
     * @return NormalizerInterface
     * @throws \Exception
     */
    public function getNormalizer($item)
    {
        foreach ($this->normalizerDelegateList as $delegate) {
            if ($delegate->supportsNormalization($item)) {
                return $delegate;
            }
        }

        throw new \Exception(sprintf(
            'No normalizer found for item : %s',
            get_class($item)
        ));
    }

    /**
     * @param \DOMNode $node
     *
     * @return DenormalizerInterface
     * @throws \Exception
     */
    public function getDenormalizer(\DOMNode $node)
    {
        foreach ($this->denormalizerDelegateList as $delegate) {
            if ($delegate->supportsDenormalization($node)) {
                return $delegate;
            }
        }

        throw new \Exception(sprintf(
            'No denormalizer found for node : %s/%s/%s',
            $node->nodeName,
            $node->nodeType,
            $node->nodeValue
        ));
    }

    /**
     * @param \DOMNode $node
     *
     * @return \DOMNode[]
     */
    protected function extractChildNodeList(\DOMNode $node)
    {
        $nodeItemList = [];
        $itemCount = $node->childNodes->length;
        for ($counter = 0; $counter < $itemCount; $counter++) {
            $nodeItemList[] = $node->childNodes->item($counter);
        }

        return $nodeItemList;
    }

    /**
     * @param \DOMDocument $document
     * @param string       $name
     * @param string|null  $value
     *
     * @return \DOMElement
     */
    protected function createElementNode(\DOMDocument $document, $name, $value = null)
    {
        return $document->createElement($name, $value);
    }
}

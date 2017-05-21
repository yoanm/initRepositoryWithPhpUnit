<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class NodeNormalizer implements DelegatedNodeNormalizerInterface
{
    /** @var NormalizerInterface[] */
    private $normalizerDelegateList = [];
    /** @var DenormalizerInterface[] */
    private $denormalizerDelegateList = [];
    /** @var NodeNormalizerHelper */
    private $nodeNormalizerHelper;

    /**
     * @param NodeNormalizerHelper                          $nodeNormalizerHelper
     * @param NormalizerInterface[]|DenormalizerInterface[] $delegateList
     */
    public function __construct(NodeNormalizerHelper $nodeNormalizerHelper, array $delegateList = [])
    {
        foreach ($delegateList as $delegate) {
            if ($delegate instanceof NormalizerInterface) {
                $this->normalizerDelegateList[] = $delegate;
            }
            if ($delegate instanceof DenormalizerInterface) {
                $this->denormalizerDelegateList[] = $delegate;
            }
        }
        $this->nodeNormalizerHelper = $nodeNormalizerHelper;
    }

    /**
     * @return NodeNormalizerHelper
     */
    public function getHelper()
    {
        return $this->nodeNormalizerHelper;
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
     * @param \DOMNode $domNode
     *
     * @return DenormalizerInterface
     * @throws \Exception
     */
    public function getDenormalizer(\DOMNode $domNode)
    {
        foreach ($this->denormalizerDelegateList as $delegate) {
            if ($delegate->supportsDenormalization($domNode)) {
                return $delegate;
            }
        }

        throw new \Exception(sprintf(
            'No denormalizer found for node : %s/%s/%s',
            $domNode->nodeName,
            $domNode->nodeType,
            $domNode->nodeValue
        ));
    }
}

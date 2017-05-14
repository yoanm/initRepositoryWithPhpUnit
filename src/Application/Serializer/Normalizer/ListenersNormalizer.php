<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Listeners\ListenerNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;

class ListenersNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'listeners';

    public function __construct(
        ListenerNormalizer $listenerNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $listenerNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param Listeners    $listeners
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($listeners, \DOMDocument $document)
    {
        $listenerListNode = $this->createElementNode($document, self::NODE_NAME);
        foreach ($listeners->getItemList() as $item) {
            $listenerListNode->appendChild(
                $itemNode = $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $listenerListNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Listeners
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new Listeners($itemList);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf Listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

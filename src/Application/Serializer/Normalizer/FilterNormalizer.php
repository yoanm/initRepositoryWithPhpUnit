<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\BaseNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter\WhiteListNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

class FilterNormalizer extends BaseNodeNormalizer implements DenormalizerInterface, NormalizerInterface
{
    const NODE_NAME = 'filter';

    public function __construct(
        WhiteListNormalizer $whiteListNormalizer,
        UnmanagedNodeNormalizer $unmanagedNodeNormalizer
    ) {
        parent::__construct([
            $whiteListNormalizer,
            $unmanagedNodeNormalizer,
        ]);
    }

    /**
     * @param Filter       $filter
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function normalize($filter, \DOMDocument $document)
    {
        $filterNode = $this->createElementNode($document, self::NODE_NAME);

        foreach ($filter->getItemList() as $item) {
            $filterNode->appendChild(
                $this->getNormalizer($item)->normalize($item, $document)
            );
        }

        return $filterNode;
    }

    /**
     * @param \DOMNode $node
     *
     * @return Filter
     */
    public function denormalize(\DOMNode $node)
    {
        $itemList = [];
        foreach ($this->extractChildNodeList($node) as $itemNode) {
            $itemList[] = $this->getDenormalizer($itemNode)->denormalize($itemNode);
        }

        return new Filter($itemList);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        return $item instanceOf Filter;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        return self::NODE_NAME === $node->nodeName;
    }
}

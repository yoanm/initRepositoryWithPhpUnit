<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Helper;

use Yoanm\PhpUnitConfigManager\Application\Creator\BlockListCreator;
use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DelegatedNodeNormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class NodeNormalizerHelper
{
    /** @var BlockListCreator */
    private $blockListCreator;

    /**
     * @param BlockListCreator $blockListCreator
     */
    public function __construct(BlockListCreator $blockListCreator)
    {
        $this->blockListCreator = $blockListCreator;
    }

    /**
     * @param \DOMNode[]                       $nodeList
     * @param DelegatedNodeNormalizerInterface $delegatedNodeNormalizer
     *
     * @return Block[]
     *
     * @throws \Exception
     */
    public function denormalizeChildNodeList(array $nodeList, DelegatedNodeNormalizerInterface $delegatedNodeNormalizer)
    {
        $itemList = [];
        foreach ($nodeList as $node) {
            $itemList[] = $delegatedNodeNormalizer->getDenormalizer($node)->denormalize($node);
        }

        return $this->blockListCreator->create($itemList);
    }

    /**
     * @param Node                             $configurationNode
     * @param DelegatedNodeNormalizerInterface $delegatedNodeNormalizer
     *
     * @return NormalizedNode[]
     */
    public function normalizeBlockList(
        Node $configurationNode,
        DelegatedNodeNormalizerInterface $delegatedNodeNormalizer
    ) {
        $list = [];
        foreach ($configurationNode->getBlockList() as $block) {
            foreach ($this->normalizeBlock($block, $delegatedNodeNormalizer) as $childNode) {
                $list[] = $childNode;
            }
        }

        return $list;
    }

    /**
     * @param Block                            $block
     * @param DelegatedNodeNormalizerInterface $delegatedNodeNormalizer
     *
     * @return NormalizedNode[]
     */
    protected function normalizeBlock(
        Block $block,
        DelegatedNodeNormalizerInterface $delegatedNodeNormalizer
    ) {
        $list = [];
        foreach ($block->getHeaderNodeList() as $header) {
            $list[] = $delegatedNodeNormalizer->getNormalizer($header)->normalize($header);
        }
        if ($block->getItem()) {
            $list[] = $delegatedNodeNormalizer->getNormalizer($block->getItem())->normalize($block->getItem());
        }
        foreach ($block->getFooterNodeList() as $footer) {
            $list[] = $delegatedNodeNormalizer->getNormalizer($footer)->normalize($footer);
        }

        return $list;
    }
}

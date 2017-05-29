<?php
namespace Yoanm\PhpUnitConfigManager\Application\Creator;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class BlockListCreator
{
    /** @var HeaderFooterHelper */
    private $headerFooterHelper;

    /**
     * @param HeaderFooterHelper    $headerFooterHelper
     */
    public function __construct(HeaderFooterHelper $headerFooterHelper)
    {
        $this->headerFooterHelper = $headerFooterHelper;
    }

    /**
     * @param ConfigurationItemInterface[]  $itemList
     * @param bool|false                    $managedOnly
     *
     * @return Block[]
     */
    public function create(array $itemList, $managedOnly = false)
    {
        $groupedItemList = $this->doItemGrouping($itemList);

        if (true === $managedOnly) {
            return array_filter($groupedItemList, function (Block $item) {
                return !$item->getItem() instanceof UnmanagedNode;
            });
        }

        $groupedItemList = $this->extractLastTrailingTextNode($groupedItemList);

        return $groupedItemList;
    }

    /**
     * @param ConfigurationItemInterface[]  $itemList
     *
     * @return Block[]
     * @throws \Exception
     */
    protected function doItemGrouping(array $itemList)
    {
        $groupedItemList = [];
        $currentUnmanagedNodeList = [];
        while ($item = array_shift($itemList)) {
            if ($item instanceof UnmanagedNode) {
                $currentUnmanagedNodeList[] = $item;
            } else {
                // Check if header comment exist if previous nodes
                $headerNodeList = $this->headerFooterHelper->extractHeaderOrLeadingSpaceNode($currentUnmanagedNodeList);
                $currentUnmanagedNodeList = $this->headerFooterHelper->updateListIfHeader(
                    $headerNodeList,
                    $currentUnmanagedNodeList
                );

                // Check if footer comment exist if base node list
                $footerNodeList = $this->headerFooterHelper->extractNodeFooterList($itemList, $headerNodeList);
                $itemList = $this->headerFooterHelper->updateListIfFooter($itemList, $footerNodeList);

                if (count($currentUnmanagedNodeList)) {
                    $groupedItemList[] = new Block(
                        array_shift($currentUnmanagedNodeList),
                        [],
                        $currentUnmanagedNodeList
                    );
                    $currentUnmanagedNodeList = [];
                }
                $groupedItemList[] = new Block($item, $headerNodeList, $footerNodeList);
            }
        }

        // Append potential remaining unamaged node
        if (count($currentUnmanagedNodeList)) {
            $groupedItemList[] = new Block(array_shift($currentUnmanagedNodeList), [], $currentUnmanagedNodeList);
        }

        return $groupedItemList;
    }

    /**
     * Will extract last leading text node and append it again in a new Block node
     * @param Block[] $groupedItemList
     *
     * @return Block[]
     */
    private function extractLastTrailingTextNode(array $groupedItemList)
    {
        if (0 === count($groupedItemList)) {
            return $groupedItemList;
        }
        $lastGroup = array_pop($groupedItemList);
        $footerNodeList = $lastGroup ? $lastGroup->getFooterNodeList() : [];
        /** @var UnmanagedNode|null $potentialEndTextNode */
        $potentialEndTextNode = array_pop($footerNodeList);

        if ($potentialEndTextNode instanceof UnmanagedNode
            && $potentialEndTextNode->getValue() instanceof \DOMText
            && false !== strpos($potentialEndTextNode->getValue()->nodeValue, "\n")
        ) {
            // If footer node exist and it's a text node
            // => Keep it to append it after new nodes but keep the rest of block above new nodes
            $groupedItemList[] = new Block(
                $lastGroup->getItem(),
                $lastGroup->getHeaderNodeList(),
                $footerNodeList
            );
            $groupedItemList[] = new Block($potentialEndTextNode);
        } else {
            $groupedItemList[] = $lastGroup;
        }

        return $groupedItemList;
    }
}

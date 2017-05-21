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
}

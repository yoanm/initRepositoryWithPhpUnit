<?php
namespace Technical\Integration\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\FilterInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\FilterInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class FilterInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilterInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new FilterInputItemTransformer();
    }

    public function testExtractWithNothing()
    {
        $this->assertNull(
            $this->transformer->extract([])
        );
    }

    public function testExtractFileAndDirectory()
    {
        $dir1 = 'dir1';
        $dir2 = 'dir2';
        $file1 = 'file1';
        $file2 = 'file2';
        $inputList = [
            InputTransformer::KEY_FILTER_WHITELIST_DIRECTORY => [$dir1, $dir2],
            InputTransformer::KEY_FILTER_WHITELIST_FILE => [$file1, $file2],
        ];

        $filter = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Filter::class, $filter);
        $whiteListList = $filter->getItemList();
        $this->assertCount(1, $whiteListList);
        $this->assertContainsOnlyInstancesOf(WhiteList::class, $whiteListList);
        /** @var WhiteList $whiteList */
        $whiteList = array_shift($whiteListList);

        $whiteListItemList = $whiteList->getItemList();
        $this->assertCount(4, $whiteListItemList);
        $this->assertContainsOnlyInstancesOf(WhiteListItem::class, $whiteListItemList);
        /** @var WhiteListItem $item */
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file1, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file2, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir1, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir2, $item->getValue());
    }

    public function testExtractExcluded()
    {
        $dir1 = 'dir1';
        $dir2 = 'dir2';
        $file1 = 'file1';
        $file2 = 'file2';
        $inputList = [
            InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_FILE => [$file1, $file2],
            InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY => [$dir1, $dir2],
        ];

        $filter = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Filter::class, $filter);
        $whiteListList = $filter->getItemList();
        $this->assertCount(1, $whiteListList);
        $this->assertContainsOnlyInstancesOf(WhiteList::class, $whiteListList);
        /** @var WhiteList $whiteList */
        $whiteList = array_shift($whiteListList);
        $whiteListItemListList = $whiteList->getItemList();
        $this->assertCount(1, $whiteListItemListList);
        $this->assertContainsOnlyInstancesOf(ExcludedWhiteList::class, $whiteListItemListList);
        /** @var ExcludedWhiteList $excludedWhiteList */
        $excludedWhiteList = array_shift($whiteListItemListList);
        $whiteListItemList = $excludedWhiteList->getItemList();
        $this->assertCount(4, $whiteListItemList);
        $this->assertContainsOnlyInstancesOf(WhiteListItem::class, $whiteListItemList);
        /** @var WhiteListItem $item */
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file1, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file2, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir1, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir2, $item->getValue());
    }

    public function testExtractAll()
    {
        $dir1 = 'dir1';
        $dir2 = 'dir2';
        $file1 = 'file1';
        $file2 = 'file2';
        $inputList = [
            InputTransformer::KEY_FILTER_WHITELIST_FILE => [$file1],
            InputTransformer::KEY_FILTER_WHITELIST_DIRECTORY => [$dir1],
            InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_FILE => [$file2],
            InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY => [$dir2],
        ];

        $filter = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Filter::class, $filter);
        $whiteListList = $filter->getItemList();
        $this->assertCount(1, $whiteListList);
        $this->assertContainsOnlyInstancesOf(WhiteList::class, $whiteListList);
        /** @var WhiteList $whiteList */
        $whiteList = array_shift($whiteListList);
        $whiteListItemListList = $whiteList->getItemList();
        $this->assertCount(3, $whiteListItemListList);
        /** @var WhiteListItem $item */
        $item = array_shift($whiteListItemListList);
        $this->assertInstanceOf(WhiteListItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file1, $item->getValue());
        $item = array_shift($whiteListItemListList);
        $this->assertInstanceOf(WhiteListItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir1, $item->getValue());


        /** @var ExcludedWhiteList $excludedItem */
        $excludedItem = array_shift($whiteListItemListList);
        $this->assertInstanceOf(ExcludedWhiteList::class, $excludedItem);
        $whiteListItemList = $excludedItem->getItemList();
        $this->assertCount(2, $whiteListItemList);
        $this->assertContainsOnlyInstancesOf(WhiteListItem::class, $whiteListItemList);
        /** @var WhiteListItem $item */
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame($file2, $item->getValue());
        $item = array_shift($whiteListItemList);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame($dir2, $item->getValue());
    }
}

<?php
namespace Technical\Integration\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\TestSuitesInputItemTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\TestSuitesInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class TestSuitesInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TestSuitesInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new TestSuitesInputItemTransformer();
    }

    public function testExtractWithNothing()
    {
        $this->assertNull(
            $this->transformer->extract([])
        );
    }

    public function testExtract()
    {
        $inputList = [
            InputTransformer::KEY_TEST_SUITE_FILE => [
                'suite1'.InputTransformer::SEPARATOR.'file1',
                'suite2'.InputTransformer::SEPARATOR.'file3',
                'suite1'.InputTransformer::SEPARATOR.'file2',
            ],
            InputTransformer::KEY_TEST_SUITE_DIRECTORY => [
                'suite1'.InputTransformer::SEPARATOR.'dir1',
                'suite2'.InputTransformer::SEPARATOR.'dir3',
                'suite1'.InputTransformer::SEPARATOR.'dir2',
            ],
            InputTransformer::KEY_TEST_SUITE_EXCLUDED => [
                'suite1'.InputTransformer::SEPARATOR.'excluded1',
                'suite2'.InputTransformer::SEPARATOR.'excluded3',
                'suite1'.InputTransformer::SEPARATOR.'excluded2',
            ],
        ];

        $testSuites = $this->transformer->extract($inputList);

        $this->assertInstanceOf(TestSuites::class, $testSuites);
        $suiteList = $testSuites->getItemList();
        $this->assertCount(2, $suiteList);
        $this->assertContainsOnlyInstancesOf(TestSuite::class, $suiteList);

        /** @var TestSuite $suite */
        $suite = array_shift($suiteList);
        $this->assertSame('suite1', $suite->getName());
        $itemList = $suite->getItemList();
        $this->assertCount(6, $itemList);
        /** @var TestSuiteItem $item */
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame('file1', $item->getValue());
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame('file2', $item->getValue());
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame('dir1', $item->getValue());
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame('dir2', $item->getValue());
        /** @var ExcludedTestSuiteItem $item */
        $item = array_shift($itemList);
        $this->assertInstanceOf(ExcludedTestSuiteItem::class, $item);
        $this->assertSame('excluded1', $item->getValue());
        $item = array_shift($itemList);
        $this->assertInstanceOf(ExcludedTestSuiteItem::class, $item);
        $this->assertSame('excluded2', $item->getValue());

        /** @var TestSuite $suite */
        $suite = array_shift($suiteList);
        $this->assertSame('suite2', $suite->getName());
        $itemList = $suite->getItemList();
        $this->assertCount(3, $itemList);
        /** @var TestSuiteItem $item */
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_FILE, $item->getType());
        $this->assertSame('file3', $item->getValue());
        $item = array_shift($itemList);
        $this->assertInstanceOf(TestSuiteItem::class, $item);
        $this->assertSame(FilesystemItem::TYPE_DIRECTORY, $item->getType());
        $this->assertSame('dir3', $item->getValue());
        /** @var ExcludedTestSuiteItem $item */
        $item = array_shift($itemList);
        $this->assertInstanceOf(ExcludedTestSuiteItem::class, $item);
        $this->assertSame('excluded3', $item->getValue());
    }
}

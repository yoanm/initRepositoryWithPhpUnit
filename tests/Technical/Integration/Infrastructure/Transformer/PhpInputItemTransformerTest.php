<?php
namespace Technical\Integration\Yoanm\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\PhpInputItemTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\PhpInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class PhpInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var PhpInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new PhpInputItemTransformer();
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
            InputTransformer::KEY_PHP => [
                'key1'.InputTransformer::SEPARATOR.'nodeValue1',
                'key2'.InputTransformer::SEPARATOR.'name1'.InputTransformer::SEPARATOR.'value1',
                'key3'.InputTransformer::SEPARATOR.'nodeValue3'
                    .InputTransformer::SEPARATOR.'name2'.InputTransformer::SEPARATOR.'value2'
            ]
        ];

        $php = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Php::class, $php);
        $itemList = $php->getItemList();
        $this->assertContainsOnlyInstancesOf(PhpItem::class, $itemList);
        $this->assertCount(3, $itemList);
        /** @var PhpItem $phpItem */
        $phpItem = array_shift($itemList);
        $this->assertSame('key1', $phpItem->getName());
        $this->assertSame('nodeValue1', $phpItem->getValue());
        $this->assertSame([], $phpItem->getAttributeList());

        $phpItem = array_shift($itemList);
        $this->assertSame('key2', $phpItem->getName());
        $this->assertSame(null, $phpItem->getValue());
        $phpItemAttributeList = $phpItem->getAttributeList();
        $this->assertContainsOnlyInstancesOf(Attribute::class, $phpItemAttributeList);
        $this->assertCount(2, $phpItemAttributeList);
        /** @var Attribute $phpItemAttribute */
        $phpItemAttribute = array_shift($phpItemAttributeList);
        $this->assertSame('name', $phpItemAttribute->getName());
        $this->assertSame('name1', $phpItemAttribute->getValue());
        $phpItemAttribute = array_shift($phpItemAttributeList);
        $this->assertSame('value', $phpItemAttribute->getName());
        $this->assertSame('value1', $phpItemAttribute->getValue());

        $phpItem = array_shift($itemList);
        $this->assertSame('key3', $phpItem->getName());
        $this->assertSame('nodeValue3', $phpItem->getValue());
        $phpItemAttributeList = $phpItem->getAttributeList();
        $this->assertContainsOnlyInstancesOf(Attribute::class, $phpItemAttributeList);
        $this->assertCount(2, $phpItemAttributeList);
        /** @var Attribute $phpItemAttribute */
        $phpItemAttribute = array_shift($phpItemAttributeList);
        $this->assertSame('name', $phpItemAttribute->getName());
        $this->assertSame('name2', $phpItemAttribute->getValue());
        $phpItemAttribute = array_shift($phpItemAttributeList);
        $this->assertSame('value', $phpItemAttribute->getName());
        $this->assertSame('value2', $phpItemAttribute->getValue());
    }
}

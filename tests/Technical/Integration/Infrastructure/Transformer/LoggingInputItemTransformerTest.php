<?php
namespace Technical\Integration\Yoanm\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\LoggingInputItemTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\LoggingInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class LoggingInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoggingInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new LoggingInputItemTransformer();
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
            InputTransformer::KEY_LOG => [
                'type1'.InputTransformer::SEPARATOR.'target1',
                'type2'.InputTransformer::SEPARATOR.'target2'
                .InputTransformer::SEPARATOR.'attr'.InputTransformer::SEPARATOR.'attrValue'
                .InputTransformer::SEPARATOR.'attr2'.InputTransformer::SEPARATOR.'attrValue2',
            ]
        ];

        $listeners = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Logging::class, $listeners);
        $itemList = $listeners->getItemList();
        $this->assertContainsOnlyInstancesOf(Log::class, $itemList);
        $this->assertCount(2, $itemList);

        /** @var Log $log */
        $log = array_shift($itemList);
        $logAttributeList = $log->getAttributeList();
        $this->assertContainsOnlyInstancesOf(Attribute::class, $logAttributeList);
        $this->assertCount(2, $logAttributeList);
        /** @var Attribute $logAttribute */
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('type', $logAttribute->getName());
        $this->assertSame('type1', $logAttribute->getValue());
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('target', $logAttribute->getName());
        $this->assertSame('target1', $logAttribute->getValue());

        /** @var Log $log */
        $log = array_shift($itemList);
        $logAttributeList = $log->getAttributeList();
        $this->assertContainsOnlyInstancesOf(Attribute::class, $logAttributeList);
        $this->assertCount(4, $logAttributeList);
        /** @var Attribute $logAttribute */
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('type', $logAttribute->getName());
        $this->assertSame('type2', $logAttribute->getValue());
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('target', $logAttribute->getName());
        $this->assertSame('target2', $logAttribute->getValue());
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('attr', $logAttribute->getName());
        $this->assertSame('attrValue', $logAttribute->getValue());
        $logAttribute = array_shift($logAttributeList);
        $this->assertSame('attr2', $logAttribute->getName());
        $this->assertSame('attrValue2', $logAttribute->getValue());
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    public function extract(array $inputList)
    {
        $logEntryList = $this->extractLogEntryList($inputList);

        if (count($logEntryList)) {
            return new Logging($logEntryList);
        }

        return null;
    }

    /**
     * @param array $inputList
     * @return array
     */
    protected function extractLogEntryList(array $inputList)
    {
        $logEntryList = [];
        if ($this->inputValueListExistFor($inputList, InputTransformer::KEY_LOG)) {
            foreach ($inputList[InputTransformer::KEY_LOG] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $type = array_shift($data);
                $target = array_shift($data);
                $attributeList = $this->convertToAttributeList($data);
                array_unshift(
                    $attributeList,
                    new Attribute('type', $type),
                    new Attribute('target', $target)
                );
                $logEntryList[] = new Log($attributeList);
            }
        }

        return $logEntryList;
    }
}

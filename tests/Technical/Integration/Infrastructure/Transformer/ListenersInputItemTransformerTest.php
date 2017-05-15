<?php
namespace Technical\Integration\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\ListenersInputItemTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\ListenersInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class ListenersInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ListenersInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new ListenersInputItemTransformer();
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
            InputTransformer::KEY_LISTENER => [
                'Class1',
                'Class2'.InputTransformer::SEPARATOR.'file.php'
            ]
        ];

        $listeners = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Listeners::class, $listeners);
        $itemList = $listeners->getItemList();
        $this->assertContainsOnlyInstancesOf(Listener::class, $itemList);
        $this->assertCount(2, $itemList);
        /** @var Listener $listener */
        $listener = array_shift($itemList);
        $this->assertSame('Class1', $listener->getClass());
        $this->assertSame(null, $listener->getFile());
        $listener = array_shift($itemList);
        $this->assertSame('Class2', $listener->getClass());
        $this->assertSame('file.php', $listener->getFile());
    }
}

<?php
namespace Technical\Integration\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\FilterInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\GroupsInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\ListenersInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\LoggingInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\PhpInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\TestSuitesInputItemTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class InputTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TestSuitesInputItemTransformer|ObjectProphecy */
    private $testSuitesInputItemTransformer;
    /** @var GroupsInputItemTransformer|ObjectProphecy */
    private $groupsInputItemTransformer;
    /** @var FilterInputItemTransformer|ObjectProphecy */
    private $filterInputItemTransformer;
    /** @var LoggingInputItemTransformer|ObjectProphecy */
    private $loggingInputItemTransformer;
    /** @var ListenersInputItemTransformer|ObjectProphecy */
    private $listenersInputItemTransformer;
    /** @var PhpInputItemTransformer|ObjectProphecy */
    private $phpInputItemTransformer;
    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;
    /** @var InputTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->testSuitesInputItemTransformer = $this->prophesize(TestSuitesInputItemTransformer::class);
        $this->groupsInputItemTransformer = $this->prophesize(GroupsInputItemTransformer::class);
        $this->filterInputItemTransformer = $this->prophesize(FilterInputItemTransformer::class);
        $this->loggingInputItemTransformer = $this->prophesize(LoggingInputItemTransformer::class);
        $this->listenersInputItemTransformer = $this->prophesize(ListenersInputItemTransformer::class);
        $this->phpInputItemTransformer = $this->prophesize(PhpInputItemTransformer::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->transformer = new InputTransformer(
            $this->testSuitesInputItemTransformer->reveal(),
            $this->groupsInputItemTransformer->reveal(),
            $this->filterInputItemTransformer->reveal(),
            $this->loggingInputItemTransformer->reveal(),
            $this->listenersInputItemTransformer->reveal(),
            $this->phpInputItemTransformer->reveal(),
            $this->serializer->reveal()
        );
    }

    public function testReturnNullIfNoInputDefined()
    {
        $this->assertNull(
            $this->transformer->fromCommandLine([])
        );
    }

    public function testInputListAreFiltered()
    {
        $this->assertNull(
            $this->transformer->fromCommandLine([
                InputTransformer::KEY_CONFIG_ATTR => [],
            ])
        );
    }

    public function testEmptyInputListAreFiltered()
    {
        $this->assertNull(
            $this->transformer->fromCommandLine([
                'unknowKey' => 'value',
                'unknowKey2' => 'value2',
            ])
        );
    }

    public function testExtractFullConfiguration()
    {
        /** @var TestSuites|ObjectProphecy $testSuites */
        $testSuites = $this->prophesize(TestSuites::class);
        /** @var Groups|ObjectProphecy $groups */
        $groups = $this->prophesize(Groups::class);
        /** @var Filter|ObjectProphecy $filter */
        $filter = $this->prophesize(Filter::class);
        /** @var Logging|ObjectProphecy $logging */
        $logging = $this->prophesize(Logging::class);
        /** @var Listeners|ObjectProphecy $listeners */
        $listeners = $this->prophesize(Listeners::class);
        /** @var Php|ObjectProphecy $php */
        $php = $this->prophesize(Php::class);
        $inputList = [
            InputTransformer::KEY_CONFIG_ATTR => [
                'attr1'.InputTransformer::SEPARATOR.'value1',
            ],
        ];
        $this->testSuitesInputItemTransformer->extract($inputList)
            ->willReturn($testSuites->reveal())
            ->shouldBeCalled();
        $this->groupsInputItemTransformer->extract($inputList)
            ->willReturn($groups->reveal())
            ->shouldBeCalled();
        $this->filterInputItemTransformer->extract($inputList)
            ->willReturn($filter->reveal())
            ->shouldBeCalled();
        $this->loggingInputItemTransformer->extract($inputList)
            ->willReturn($logging->reveal())
            ->shouldBeCalled();
        $this->listenersInputItemTransformer->extract($inputList)
            ->willReturn($listeners->reveal())
            ->shouldBeCalled();
        $this->phpInputItemTransformer->extract($inputList)
            ->willReturn($php->reveal())
            ->shouldBeCalled();

        $configurationFile = $this->transformer->fromCommandLine($inputList);

        $this->assertInstanceOf(ConfigurationFile::class, $configurationFile);
        $configurationFileNodeList = $configurationFile->getNodeList();
        $this->assertContainsOnlyInstancesOf(Configuration::class, $configurationFileNodeList);
        /** @var Configuration $configuration */
        $configuration = array_shift($configurationFileNodeList);
        $configurationItemList = $configuration->getItemList();
        $this->assertContainsOnlyInstancesOf(ConfigurationItemInterface::class, $configurationItemList);
        $this->assertCount(6, $configurationItemList);
        $this->assertSame($testSuites->reveal(), array_shift($configurationItemList));
        $this->assertSame($groups->reveal(), array_shift($configurationItemList));
        $this->assertSame($filter->reveal(), array_shift($configurationItemList));
        $this->assertSame($logging->reveal(), array_shift($configurationItemList));
        $this->assertSame($listeners->reveal(), array_shift($configurationItemList));
        $this->assertSame($php->reveal(), array_shift($configurationItemList));

        $attributeList = $configuration->getAttributeList();
        $this->assertContainsOnlyInstancesOf(Attribute::class, $attributeList);
        $this->assertCount(1, $attributeList);
        /** @var Attribute $attribute */
        $attribute = array_shift($attributeList);
        $this->assertSame('attr1', $attribute->getName());
        $this->assertSame('value1', $attribute->getValue());
    }

    public function testReturnOnlyDefinedConfigurationItem()
    {
        /** @var Filter|ObjectProphecy $filter */
        $filter = $this->prophesize(Filter::class);
        /** @var Php|ObjectProphecy $php */
        $php = $this->prophesize(Php::class);
        $inputList = [
            InputTransformer::KEY_CONFIG_ATTR => [
                'attr1'.InputTransformer::SEPARATOR.'value1',
            ],
        ];
        $this->testSuitesInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->groupsInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->filterInputItemTransformer->extract($inputList)
            ->willReturn($filter->reveal())
            ->shouldBeCalled();
        $this->loggingInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->listenersInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->phpInputItemTransformer->extract($inputList)
            ->willReturn($php->reveal())
            ->shouldBeCalled();

        $configurationFile = $this->transformer->fromCommandLine($inputList);

        $this->assertInstanceOf(ConfigurationFile::class, $configurationFile);
        $configurationFileNodeList = $configurationFile->getNodeList();
        $this->assertContainsOnlyInstancesOf(Configuration::class, $configurationFileNodeList);
        /** @var Configuration $configuration */
        $configuration = array_shift($configurationFileNodeList);
        $configurationItemList = $configuration->getItemList();
        $this->assertContainsOnlyInstancesOf(ConfigurationItemInterface::class, $configurationItemList);
        $this->assertCount(2, $configurationItemList);
        $this->assertSame($filter->reveal(), array_shift($configurationItemList));
        $this->assertSame($php->reveal(), array_shift($configurationItemList));
    }

    public function testPrettifyOnDemand()
    {
        $inputList = [
            InputTransformer::KEY_CONFIG_ATTR => [
                'attr1'.InputTransformer::SEPARATOR.'value1',
            ],
        ];
        $serialized = 'serialized';
        $this->testSuitesInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->groupsInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->filterInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->loggingInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->listenersInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();
        $this->phpInputItemTransformer->extract($inputList)
            ->willReturn(null)
            ->shouldBeCalled();

        $configurationFile = null;
        $this->serializer->serialize(
            Argument::type(ConfigurationFile::class),
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => false,
                PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => LIBXML_NOBLANKS,
            ]
        )
            ->will(function ($args) use (&$configurationFile, $serialized) {
                $configurationFile = $args[0];

                return $serialized;
            })
            ->shouldBeCalled();


        $this->serializer->deserialize(
            $serialized,
            ConfigurationFile::class,
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => false
            ]
        )
            ->willReturn($configurationFile)
            ->shouldBeCalled();

        $this->assertSame(
            $configurationFile,
            $this->transformer->fromCommandLine($inputList, true)
        );
        $this->assertInstanceOf(ConfigurationFile::class, $configurationFile);
    }
}

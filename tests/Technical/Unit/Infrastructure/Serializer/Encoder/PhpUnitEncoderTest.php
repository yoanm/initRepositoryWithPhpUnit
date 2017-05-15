<?php
namespace Technical\Unit\Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder\PhpUnitEncoder as AppEncoder;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder
 */
class PhpUnitEncoderTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppEncoder|ObjectProphecy */
    private $appEncoder;
    /** @var PhpUnitEncoder */
    private $encoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appEncoder = $this->prophesize(AppEncoder::class);
        $this->encoder = new PhpUnitEncoder(
            $this->appEncoder->reveal()
        );
    }

    public function testEncodeWithoutContext()
    {
        /** @var \DOMDocument|ObjectProphecy $data */
        $data = $this->prophesize(\DOMDocument::class);
        $encodedData = 'encoded_data';

        $this->appEncoder->encode(Argument::type(\DOMDocument::class), null, null)
            ->willReturn($encodedData)
            ->shouldBeCalled();

        $this->assertSame(
            $encodedData,
            $this->encoder->encode($data->reveal(), PhpUnitEncoder::FORMAT)
        );
    }

    public function testEncodeWithContext()
    {
        /** @var \DOMDocument|ObjectProphecy $data */
        $data = $this->prophesize(\DOMDocument::class);
        $encodedData = 'encoded_data';
        $formatOutputContextValue = 'FORMAT_OUTPUT_CONTEXT_KEY';
        $preserveWhitespaceContextValue = 'PRESERVE_WHITESPACE_CONTEXT_KEY';
        $context = [
            PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => $formatOutputContextValue,
            PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => $preserveWhitespaceContextValue,
            'unknowKey' => true,
        ];

        $this->appEncoder->encode(
            Argument::type(\DOMDocument::class),
            $formatOutputContextValue,
            $preserveWhitespaceContextValue
        )
            ->willReturn($encodedData)
            ->shouldBeCalled();

        $this->assertSame(
            $encodedData,
            $this->encoder->encode($data->reveal(), PhpUnitEncoder::FORMAT, $context)
        );
    }

    public function testDecodeWithoutContext()
    {
        $data = ['data'];
        $encodedData = 'encoded_data';

        $this->appEncoder->decode($encodedData, null, null, null)
            ->willReturn($data)
            ->shouldBeCalled();

        $this->assertSame(
            $data,
            $this->encoder->decode($encodedData, PhpUnitEncoder::FORMAT)
        );
    }

    public function testDecodeWithContext()
    {
        $data = ['data'];
        $encodedData = 'encoded_data';
        $formatOutputContextValue = 'FORMAT_OUTPUT_CONTEXT_KEY';
        $preserveWhitespaceContextValue = 'PRESERVE_WHITESPACE_CONTEXT_KEY';
        $loadOptionsContextValue = 'LOAD_OPTIONS_CONTEXT_KEY';
        $context = [
            PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => $formatOutputContextValue,
            PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => $preserveWhitespaceContextValue,
            PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => $loadOptionsContextValue,
            'unknowKey' => true,
        ];

        $this->appEncoder->decode(
            $encodedData,
            $formatOutputContextValue,
            $preserveWhitespaceContextValue,
            $loadOptionsContextValue
        )
            ->willReturn($data)
            ->shouldBeCalled();

        $this->assertSame(
            $data,
            $this->encoder->decode($encodedData, PhpUnitEncoder::FORMAT, $context)
        );
    }

    /**
     * @dataProvider getTestSupportsFormatData
     *
     * @param string $format
     * @param bool   $expectedResult
     */
    public function testSupportsEncoding($format, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->encoder->supportsEncoding($format)
        );
    }

    /**
     * @dataProvider getTestSupportsFormatData
     *
     * @param string $format
     * @param bool   $expectedResult
     */
    public function testSupportsDecoding($format, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->encoder->supportsDecoding($format)
        );
    }

    /**
     * @return array
     */
    public function getTestSupportsFormatData()
    {
        return [
            'composer' => [
                'format' => PhpUnitEncoder::FORMAT,
                'expectedResult' => true
            ],
            'other' => [
                'format' => 'json',
                'expectedResult' => false
            ],
        ];
    }
}

<?php
namespace Technical\Unit\Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Normalizer;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SebastianBergmann\Comparator\DOMNodeComparatorTest;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\ConfigurationFileNormalizer as AppNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Normalizer\ConfigurationFileNormalizer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Normalizer\ConfigurationFileNormalizer
 */
class ConfigurationFileNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppNormalizer|ObjectProphecy */
    private $appConfigurationFileNormalizer;
    /** @var ConfigurationFileNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appConfigurationFileNormalizer = $this->prophesize(AppNormalizer::class);
        $this->normalizer = new ConfigurationFileNormalizer(
            $this->appConfigurationFileNormalizer->reveal()
        );
    }

    public function testEncode()
    {
        $normalizedData = 'normalized_data';

        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);

        $this->appConfigurationFileNormalizer->normalize($configurationFile->reveal())
            ->willReturn($normalizedData)
            ->shouldBeCalled();

        $this->assertSame(
            $normalizedData,
            $this->normalizer->normalize($configurationFile->reveal())
        );
    }

    public function testDecode()
    {
        /** @var \DOMNode|ObjectProphecy $normalizedData */
        $normalizedData = $this->prophesize(\DOMNode::class);
        /** @var ConfigurationFile|ObjectProphecy $configurationFile */
        $configurationFile = $this->prophesize(ConfigurationFile::class);

        $this->appConfigurationFileNormalizer->denormalize(Argument::type(\DOMNode::class))
            ->willReturn($configurationFile->reveal())
            ->shouldBeCalled();

        $this->assertSame(
            $configurationFile->reveal(),
            $this->normalizer->denormalize($normalizedData->reveal(), Configuration::class)
        );
    }

    /**
     * @dataProvider getTestSupportsClassData
     *
     * @param string $class
     * @param bool   $expectedResult
     */
    public function testSupportsNormalization($class, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->normalizer->supportsNormalization($this->prophesize($class)->reveal())
        );
    }

    /**
     * @dataProvider getTestSupportsClassData
     *
     * @param string $class
     * @param bool   $expectedResult
     */
    public function testSupportsDenormalization($class, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->normalizer->supportsDenormalization([], $class)
        );
    }

    /**
     * @return array
     */
    public function getTestSupportsClassData()
    {
        return [
            'Configuration class' => [
                'class' => ConfigurationFile::class,
                'expectedResult' => true
            ],
            'other' => [
                'class' => \stdClass::class,
                'expectedResult' => false
            ],
        ];
    }
}

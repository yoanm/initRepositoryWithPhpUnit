<?php
namespace Technical\Unit\Yoanm\PhpUnitConfigManager\Infrastructure\Loader;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Infrastructure\Loader\ConfigurationFileLoader;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Loader\ConfigurationFileLoader
 */
class ConfigurationFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Finder|ObjectProphecy */
    private $finder;
    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;
    /** @var ConfigurationFileLoader */
    private $loader;

    public function setUp()
    {
        $this->finder = $this->prophesize(Finder::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->loader = new ConfigurationFileLoader(
            $this->finder->reveal(),
            $this->serializer->reveal()
        );
    }

    public function testFromPath()
    {
        $expectedLoadedContent = 'loaded_content';
        $fileContent = 'content';
        $path = 'path';
        /** @var SplFileInfo|ObjectProphecy $file */
        $file = $this->prophesize(SplFileInfo::class);

        $this->finder->in($path)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->files()
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->name(ConfigurationFile::FILENAME)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->depth(0)
            ->willReturn([$file->reveal()]) // finder is also an iterator but it's easier to manage it like that
            ->shouldBeCalled();
        $file->getContents()
            ->willReturn($fileContent)
            ->shouldBeCalled();


        $this->serializer->deserialize(
            $fileContent,
            ConfigurationFile::class,
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => true,
                PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => null
            ]
        )
            ->willReturn($expectedLoadedContent)
            ->shouldBeCalled();

        $this->assertSame(
            $expectedLoadedContent,
            $this->loader->fromPath($path)
        );
    }

    public function testFromPathThrowExceptionIfFileNotFound()
    {
        $path = 'path';

        $this->finder->in($path)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->files()
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->name(ConfigurationFile::FILENAME)
            ->willReturn($this->finder->reveal())
            ->shouldBeCalled();
        $this->finder->depth(0)
            ->willReturn([])
            ->shouldBeCalled();

        $this->setExpectedException(
            FileNotFoundException::class,
            sprintf(
                '%s/%s',
                $path,
                ConfigurationFile::FILENAME
            )
        );

        $this->loader->fromPath($path);
    }

    public function testFromString()
    {
        $expectedLoadedContent = 'loaded_content';
        $fileContent = 'content';

        $this->serializer->deserialize(
            $fileContent,
            ConfigurationFile::class,
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => true,
                PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => null
            ]
        )
            ->willReturn($expectedLoadedContent)
            ->shouldBeCalled();

        $this->assertSame(
            $expectedLoadedContent,
            $this->loader->fromString($fileContent)
        );
    }
}

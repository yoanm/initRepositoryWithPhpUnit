<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Loader;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

class ConfigurationFileLoader implements ConfigurationFileLoaderInterface
{
    /** @var Finder */
    private $finder;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(Finder $finder, SerializerInterface $serializer)
    {
        $this->finder = $finder;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function fromPath($path)
    {
        $finder = $this->finder
            ->in($path)
            ->files()
            ->name(ConfigurationFile::FILENAME)
            ->depth(0);

        /** @var SplFileInfo|null $file */
        $file = null;
        foreach ($finder as $result) {
            $file = $result;
            break;
        }

        if (null === $file) {
            throw new FileNotFoundException(
                null,
                0,
                null,
                sprintf(
                    '%s/%s',
                    trim($path, '/'),
                    ConfigurationFile::FILENAME
                )
            );
        }

        return $this->fromString($file->getContents());
    }

    /**
     * {@inheritdoc}
     */
    public function fromString($serializedConfiguration)
    {
        return $this->serializer->deserialize(
            $serializedConfiguration,
            ConfigurationFile::class,
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => true,
                PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => null
            ]
        );
    }
}

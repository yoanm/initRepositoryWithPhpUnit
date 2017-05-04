<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Writer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Application\Writer\ConfigurationFileWriterInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

class ConfigurationFileWriter implements ConfigurationFileWriterInterface
{
    const FILENAME = 'phpunit.xml.dist';

    /** @var SerializerInterface */
    private $serializer;
    /** @var Filesystem */
    private $filesystem;

    /**
     * @param SerializerInterface $serializer
     * @param Filesystem          $filesystem
     */
    public function __construct(SerializerInterface $serializer, Filesystem $filesystem)
    {
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function write(ConfigurationFile $configurationFile, $destinationPath)
    {
        $data = $this->serializer->serialize($configurationFile, PhpUnitEncoder::FORMAT);

        $filename = sprintf(
            '%s%s%s',
            trim($destinationPath, DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR,
            self::FILENAME
        );

        $this->filesystem->dumpFile($filename, $data);
    }
}

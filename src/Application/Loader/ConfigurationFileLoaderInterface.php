<?php
namespace Yoanm\PhpUnitConfigManager\Application\Loader;

use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

interface ConfigurationFileLoaderInterface
{
    /**
     * @param string $path
     *
     * @return ConfigurationFile
     */
    public function fromPath($path);

    /**
     * @param string $serializedConfiguration
     *
     * @return ConfigurationFile
     */
    public function fromString($serializedConfiguration);
}

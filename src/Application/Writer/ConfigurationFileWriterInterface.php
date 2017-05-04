<?php
namespace Yoanm\PhpUnitConfigManager\Application\Writer;

use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

interface ConfigurationFileWriterInterface
{
    /**
     * @param ConfigurationFile $configurationFile
     * @param string            $destinationPath
     */
    public function write(ConfigurationFile $configurationFile, $destinationPath);
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application;

use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Application\Updater\ConfigurationFileUpdater;
use Yoanm\PhpUnitConfigManager\Application\Writer\ConfigurationFileWriterInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class UpdateConfigurationFileList
{
    /** @var ConfigurationFileWriterInterface */
    private $configurationWriter;
    /** @var ConfigurationFileUpdater */
    private $configurationUpdater;

    /**
     * @param ConfigurationFileWriterInterface $configurationWriter
     * @param ConfigurationFileUpdater         $configurationUpdater
     */
    public function __construct(
        ConfigurationFileWriterInterface $configurationWriter,
        ConfigurationFileUpdater $configurationUpdater
    ) {
        $this->configurationWriter = $configurationWriter;
        $this->configurationUpdater = $configurationUpdater;
    }

    /**
     * @param UpdateConfigurationFileListRequest $request
     */
    public function run(UpdateConfigurationFileListRequest $request)
    {
        $this->configurationWriter->write(
            $this->getConfiguration($request),
            $request->getDestinationFolder()
        );
    }

    /**
     * @param UpdateConfigurationFileListRequest $request
     *
     * @return ConfigurationFile
     */
    protected function getConfiguration(UpdateConfigurationFileListRequest $request)
    {
        return $this->configurationUpdater->update($request->getConfigurationFileList());
    }
}

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
    private $configurationFileUpdater;

    /**
     * @param ConfigurationFileWriterInterface $configurationWriter
     * @param ConfigurationFileUpdater         $configurationFileUpdater
     */
    public function __construct(
        ConfigurationFileWriterInterface $configurationWriter,
        ConfigurationFileUpdater $configurationFileUpdater
    ) {
        $this->configurationWriter = $configurationWriter;
        $this->configurationFileUpdater = $configurationFileUpdater;
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
        return $this->configurationFileUpdater->update($request->getConfigurationFileList());
    }
}

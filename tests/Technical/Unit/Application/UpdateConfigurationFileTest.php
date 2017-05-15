<?php
namespace Technical\Unit\Yoanm\PhpUnitConfigManager\Application;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList;
use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Application\Updater\ConfigurationFileUpdater;
use Yoanm\PhpUnitConfigManager\Application\Writer\ConfigurationFileWriterInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Writer\ConfigurationFileWriter;

/**
 * @covers Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList
 */
class UpdateConfigurationFileListTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigurationFileWriterInterface|ObjectProphecy */
    private $configurationWriter;
    /** @var ConfigurationFileUpdater|ObjectProphecy */
    private $configurationUpdater;
    /** @var UpdateConfigurationFileList */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->configurationWriter = $this->prophesize(ConfigurationFileWriter::class);
        $this->configurationUpdater = $this->prophesize(ConfigurationFileUpdater::class);

        $this->updater = new UpdateConfigurationFileList(
            $this->configurationWriter->reveal(),
            $this->configurationUpdater->reveal()
        );
    }

    public function testRun()
    {
        $destPath = 'path';
        /** @var ConfigurationFile|ObjectProphecy $baseConfigurationFile */
        $baseConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var ConfigurationFile|ObjectProphecy $newConfigurationFile */
        $newConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var ConfigurationFile|ObjectProphecy $updatedConfigurationFile */
        $updatedConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var UpdateConfigurationFileListRequest|ObjectProphecy $request */
        $request = $this->prophesize(UpdateConfigurationFileListRequest::class);
        /** @var ConfigurationFile|ObjectProphecy $templateConfigurationFile */
        $templateConfigurationFile = $this->prophesize(ConfigurationFile::class);
        /** @var ConfigurationFile|ObjectProphecy $lastUpdateConfigurationFileList */
        $lastUpdateConfigurationFileList = $this->prophesize(ConfigurationFile::class);
        $configurationFileList = [
            $templateConfigurationFile->reveal(),
            $baseConfigurationFile->reveal(),
            $newConfigurationFile->reveal()
        ];

        $request->getConfigurationFileList()
            ->willReturn($configurationFileList)
            ->shouldBeCalled();
        $request->getDestinationFolder()
            ->willReturn($destPath)
            ->shouldBeCalled();
        $this->configurationUpdater->update($configurationFileList)
            ->willReturn($lastUpdateConfigurationFileList->reveal())
            ->shouldBeCalled();
        $this->configurationWriter->write($lastUpdateConfigurationFileList->reveal(), $destPath)
            ->shouldBeCalled();

        $this->updater->run($request->reveal());
    }
}

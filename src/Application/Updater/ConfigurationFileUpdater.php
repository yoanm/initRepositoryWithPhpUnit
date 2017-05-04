<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;
    /** @var KeywordListUpdater */
    private $keywordListUpdater;
    /** @var ListUpdater */
    private $listUpdater;
    /** @var AuthorListUpdater */
    private $authorListUpdater;

    public function __construct(
        PlainValueUpdater $plainValueUpdater,
        KeywordListUpdater $keywordListUpdater,
        ListUpdater $listUpdater,
        AuthorListUpdater $authorListUpdater
    ) {
        $this->plainValueUpdater = $plainValueUpdater;
        $this->keywordListUpdater = $keywordListUpdater;
        $this->listUpdater = $listUpdater;
        $this->authorListUpdater = $authorListUpdater;
    }

    /**
     * @param ConfigurationFile[] $configurationFileList
     *
     * @return ConfigurationFile
     */
    public function update(array $configurationFileList)
    {
        $newConfigurationFile = array_pop($configurationFileList);
        while (($baseConfiguration = array_pop($configurationFileList)) instanceof ConfigurationFile) {
            $newConfigurationFile = $this->merge($baseConfiguration, $newConfigurationFile);
        }

        return $newConfigurationFile;
    }


    /**
     * @param ConfigurationFile $baseConfigurationFile
     * @param ConfigurationFile $newConfigurationFile
     *
     * @return ConfigurationFile
     */
    public function merge(ConfigurationFile $baseConfigurationFile, ConfigurationFile $newConfigurationFile)
    {
        return new ConfigurationFile(
            $this->mergeConfiguration(
                $baseConfigurationFile->getConfiguration(),
                $newConfigurationFile->getConfiguration()
            ),
            $this->mergeKeyList(
                $baseConfigurationFile->getKeyList(),
                $newConfigurationFile->getKeyList()
            )
        );
    }

    protected function mergeConfiguration(Configuration $baseConfiguration, Configuration $newConfiguration)
    {
        return new Configuration(
            $this->plainValueUpdater->update(
                $newConfiguration->getPackageName(),
                $baseConfiguration->getPackageName()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getType(),
                $baseConfiguration->getType()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getLicense(),
                $baseConfiguration->getLicense()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getPackageVersion(),
                $baseConfiguration->getPackageVersion()
            ),
            $this->plainValueUpdater->update(
                $newConfiguration->getDescription(),
                $baseConfiguration->getDescription()
            ),
            $this->keywordListUpdater->update(
                $newConfiguration->getKeywordList(),
                $baseConfiguration->getKeywordList()
            ),
            $this->authorListUpdater->update($newConfiguration->getAuthorList(), $baseConfiguration->getAuthorList()),
            $this->listUpdater->update(
                $newConfiguration->getProvidedPackageList(),
                $baseConfiguration->getProvidedPackageList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getSuggestedPackageList(),
                $baseConfiguration->getSuggestedPackageList()
            ),
            $this->listUpdater->update($newConfiguration->getSupportList(), $baseConfiguration->getSupportList()),
            $this->listUpdater->update($newConfiguration->getAutoloadList(), $baseConfiguration->getAutoloadList()),
            $this->listUpdater->update(
                $newConfiguration->getAutoloadDevList(),
                $baseConfiguration->getAutoloadDevList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getRequiredPackageList(),
                $baseConfiguration->getRequiredPackageList()
            ),
            $this->listUpdater->update(
                $newConfiguration->getRequiredDevPackageList(),
                $baseConfiguration->getRequiredDevPackageList()
            ),
            $this->listUpdater->update($newConfiguration->getScriptList(), $baseConfiguration->getScriptList()),
            $this->listUpdater->updateRaw(
                $newConfiguration->getUnmanagedPropertyList(),
                $baseConfiguration->getUnmanagedPropertyList()
            )
        );
    }

    protected function mergeKeyList(array $baseKeyList, array $newKeyList)
    {
        return array_values(array_unique(array_merge($baseKeyList, $newKeyList)));
    }
}

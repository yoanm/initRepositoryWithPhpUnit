<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

/**
 * Class ConfigurationDenormalizer
 */
class ConfigurationDenormalizer implements DenormalizerInterface
{
    /** @var AuthorListNormalizer */
    private $authorListNormalizer;
    /** @var PackageListNormalizer */
    private $packageListNormalizer;
    /** @var SuggestedPackageListNormalizer */
    private $suggestedPackageListNormalizer;
    /** @var SupportListNormalizer */
    private $supportListNormalizer;
    /** @var AutoloadListNormalizer */
    private $autoloadListNormalizer;
    /** @var ScriptListNormalizer */
    private $scriptListNormalizer;

    public function __construct(
        AuthorListNormalizer $authorListNormalizer,
        PackageListNormalizer $packageListNormalizer,
        SuggestedPackageListNormalizer $suggestedPackageListNormalizer,
        SupportListNormalizer $supportListNormalizer,
        AutoloadListNormalizer $autoloadListNormalizer,
        ScriptListNormalizer $scriptListNormalizer
    ) {
        $this->authorListNormalizer = $authorListNormalizer;
        $this->packageListNormalizer = $packageListNormalizer;
        $this->suggestedPackageListNormalizer = $suggestedPackageListNormalizer;
        $this->supportListNormalizer = $supportListNormalizer;
        $this->autoloadListNormalizer = $autoloadListNormalizer;
        $this->scriptListNormalizer = $scriptListNormalizer;
    }

    /**
     * @param array $configuration
     *
     * @return Configuration
     */
    public function denormalize(array $configuration)
    {
        return new Configuration(
            $this->valueOrNull($configuration, ConfigurationFile::KEY_NAME),
            $this->valueOrNull($configuration, ConfigurationFile::KEY_TYPE),
            $this->valueOrNull($configuration, ConfigurationFile::KEY_LICENSE),
            $this->valueOrNull($configuration, ConfigurationFile::KEY_VERSION),
            $this->valueOrNull($configuration, ConfigurationFile::KEY_DESCRIPTION),
            $this->extractKeywordList($configuration),
            $this->getNormalizedOrDefault(
                $this->authorListNormalizer,
                $configuration,
                ConfigurationFile::KEY_AUTHORS,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->packageListNormalizer,
                $configuration,
                ConfigurationFile::KEY_PROVIDE,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->suggestedPackageListNormalizer,
                $configuration,
                ConfigurationFile::KEY_SUGGEST,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->supportListNormalizer,
                $configuration,
                ConfigurationFile::KEY_SUPPORT,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->autoloadListNormalizer,
                $configuration,
                ConfigurationFile::KEY_AUTOLOAD,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->autoloadListNormalizer,
                $configuration,
                ConfigurationFile::KEY_AUTOLOAD_DEV,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->packageListNormalizer,
                $configuration,
                ConfigurationFile::KEY_REQUIRE,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->packageListNormalizer,
                $configuration,
                ConfigurationFile::KEY_REQUIRE_DEV,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->scriptListNormalizer,
                $configuration,
                ConfigurationFile::KEY_SCRIPTS,
                []
            ),
            $this->loadUnmanagedPropertyList($configuration)
        );
    }

    /**
     * @param array $configuration
     *
     * @return array
     */
    protected function loadUnmanagedPropertyList(array $configuration)
    {
        $managedKeyList = [
            ConfigurationFile::KEY_NAME => true,
            ConfigurationFile::KEY_TYPE => true,
            ConfigurationFile::KEY_LICENSE => true,
            ConfigurationFile::KEY_VERSION => true,
            ConfigurationFile::KEY_DESCRIPTION => true,
            ConfigurationFile::KEY_KEYWORDS => true,
            ConfigurationFile::KEY_AUTHORS => true,
            ConfigurationFile::KEY_PROVIDE => true,
            ConfigurationFile::KEY_SUGGEST => true,
            ConfigurationFile::KEY_SUPPORT => true,
            ConfigurationFile::KEY_REQUIRE => true,
            ConfigurationFile::KEY_REQUIRE_DEV => true,
            ConfigurationFile::KEY_AUTOLOAD => true,
            ConfigurationFile::KEY_AUTOLOAD_DEV => true,
            ConfigurationFile::KEY_SCRIPTS => true,
        ];
        $unmanagedPropertyList = [];
        foreach ($configuration as $key => $value) {
            if (!isset($managedKeyList[$key])) {
                $unmanagedPropertyList[$key] = $value;
            }
        }

        return $unmanagedPropertyList;
    }

    /**
     * @param DenormalizerInterface $denormalizer
     * @param array                 $configuration
     * @param string                $key
     * @param mixed                 $default
     *
     * @return array
     */
    protected function getNormalizedOrDefault(DenormalizerInterface $denormalizer, array $configuration, $key, $default)
    {
        return isset($configuration[$key]) ? $denormalizer->denormalize($configuration[$key]) : $default;
    }

    /**
     * @param array  $configuration
     * @param string $key
     *
     * @return string|null
     */
    protected function valueOrNull(array $configuration, $key)
    {
        return isset($configuration[$key]) ? $configuration[$key] : null;
    }

    /**
     * @param array $configuration
     *
     * @return array
     */
    protected function extractKeywordList(array $configuration)
    {
        return isset($configuration[ConfigurationFile::KEY_KEYWORDS])
            ? $configuration[ConfigurationFile::KEY_KEYWORDS]
            : [];
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationNormalizer
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

    public function normalize(Configuration $configuration)
    {
        $normalizedConfiguration = [];

        // name
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getPackageName(),
            ConfigurationFile::KEY_NAME
        );
        // type
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getType(),
            ConfigurationFile::KEY_TYPE
        );
        // license
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getLicense(),
            ConfigurationFile::KEY_LICENSE
        );
        // package version
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getPackageVersion(),
            ConfigurationFile::KEY_VERSION
        );
        // description
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getDescription(),
            ConfigurationFile::KEY_DESCRIPTION
        );
        // keywords
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $configuration->getKeywordList(),
            ConfigurationFile::KEY_KEYWORDS
        );
        // authors
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->authorListNormalizer->normalize($configuration->getAuthorList()),
            ConfigurationFile::KEY_AUTHORS
        );
        // provide
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getProvidedPackageList()),
            ConfigurationFile::KEY_PROVIDE
        );
        // suggest
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->suggestedPackageListNormalizer->normalize($configuration->getSuggestedPackageList()),
            ConfigurationFile::KEY_SUGGEST
        );
        // support
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->supportListNormalizer->normalize($configuration->getSupportList()),
            ConfigurationFile::KEY_SUPPORT
        );
        // require
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getRequiredPackageList()),
            ConfigurationFile::KEY_REQUIRE
        );
        // require-dev
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getRequiredDevPackageList()),
            ConfigurationFile::KEY_REQUIRE_DEV
        );
        // autoload
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->autoloadListNormalizer->normalize($configuration->getAutoloadList()),
            ConfigurationFile::KEY_AUTOLOAD
        );
        // autoload-dev
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->autoloadListNormalizer->normalize($configuration->getAutoloadDevList()),
            ConfigurationFile::KEY_AUTOLOAD_DEV
        );
        // script
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->scriptListNormalizer->normalize($configuration->getScriptList()),
            ConfigurationFile::KEY_SCRIPTS
        );

        return array_merge($normalizedConfiguration, $configuration->getUnmanagedPropertyList());
    }

    /**
     * @param array  $normalizedConfiguration
     * @param array  $list
     * @param string $key
     *
     * @return array
     */
    protected function appendIfNotEmpty(array $normalizedConfiguration, array $list, $key)
    {
        if (count($list)) {
            $normalizedConfiguration[$key] = $list;
        }

        return $normalizedConfiguration;
    }

    /**
     * @param array  $normalizedConfiguration
     * @param string $value
     * @param string $key
     *
     * @return array
     */
    protected function appendIfDefined(array $normalizedConfiguration, $value, $key)
    {
        if ($value) {
            $normalizedConfiguration[$key] = $value;
        }

        return $normalizedConfiguration;
    }
}

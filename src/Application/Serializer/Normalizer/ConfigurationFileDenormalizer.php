<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

/**
 * Class ConfigurationFileDenormalizer
 */
class ConfigurationFileDenormalizer implements DenormalizerInterface
{
    /** @var ConfigurationDenormalizer */
    private $configurationDenormalizer;

    public function __construct(ConfigurationDenormalizer $configurationDenormalizer)
    {
        $this->configurationDenormalizer = $configurationDenormalizer;
    }

    /**
     * @param array $configuration
     *
     * @return ConfigurationFile
     */
    public function denormalize(array $configuration)
    {
        return new ConfigurationFile(
            $this->configurationDenormalizer->denormalize($configuration),
            array_keys($configuration)
        );
    }
}

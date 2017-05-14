<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\ConfigurationFileNormalizer as AppNormalizer;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @var AppNormalizer */
    private $appNormalizer;

    /**
     * @param AppNormalizer $appNormalizer
     */
    public function __construct(AppNormalizer $appNormalizer)
    {
        $this->appNormalizer = $appNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->appNormalizer->normalize($object);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ConfigurationFile;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->appNormalizer->denormalize($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return ConfigurationFile::class == $type;
    }
}

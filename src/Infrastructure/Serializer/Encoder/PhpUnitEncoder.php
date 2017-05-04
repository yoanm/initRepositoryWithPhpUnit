<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder\PhpUnitEncoder as AppEncoder;

class PhpUnitEncoder implements EncoderInterface, DecoderInterface
{
    const FORMAT = 'phpunit';

    /** @var AppEncoder */
    private $appEncoder;

    public function __construct(AppEncoder $appEncoder)
    {
        $this->appEncoder = $appEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = array())
    {
        return $this->appEncoder->encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return $this->isSupportedFormat($format);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        return $this->appEncoder->decode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return $this->isSupportedFormat($format);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupportedFormat($format)
    {
        return self::FORMAT === $format;
    }
}

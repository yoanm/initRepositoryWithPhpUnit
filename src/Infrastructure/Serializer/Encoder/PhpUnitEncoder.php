<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder\PhpUnitEncoder as AppEncoder;

class PhpUnitEncoder implements EncoderInterface, DecoderInterface
{
    const FORMAT = 'phpunit';
    const FORMAT_OUTPUT_CONTEXT_KEY = 'format-output';
    const PRESERVE_WHITESPACE_CONTEXT_KEY = 'preserve-whitespace';
    const LOAD_OPTIONS_CONTEXT_KEY = 'load-options';

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
        return $this->appEncoder->encode(
            $data,
            isset($context[self::FORMAT_OUTPUT_CONTEXT_KEY]) ? $context[self::FORMAT_OUTPUT_CONTEXT_KEY]: null,
            isset($context[self::PRESERVE_WHITESPACE_CONTEXT_KEY])
                ? $context[self::PRESERVE_WHITESPACE_CONTEXT_KEY]
                : null
        );
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
        return $this->appEncoder->decode(
            $data,
            isset($context[self::FORMAT_OUTPUT_CONTEXT_KEY]) ? $context[self::FORMAT_OUTPUT_CONTEXT_KEY] : null,
            isset($context[self::PRESERVE_WHITESPACE_CONTEXT_KEY])
                ? $context[self::PRESERVE_WHITESPACE_CONTEXT_KEY]
                : null,
            isset($context[self::LOAD_OPTIONS_CONTEXT_KEY])
                ? $context[self::LOAD_OPTIONS_CONTEXT_KEY]
                : null
        );
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

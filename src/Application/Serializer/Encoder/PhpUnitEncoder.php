<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class PhpUnitEncoder
{
    const CONFIGURATION_ATTRIBUTE_PATTERN = '/^(?:\s|\t)*<phpunit((?:\s|\t|)+(?:[^=>]+="[^">]*")+)>(?:\s|\t)*$/m';

    /**
     * @param \DOMDocument $document
     * @param bool|null    $formatOutput
     * @param bool|null    $preserveWhiteSpace
     *
     * @return string
     */
    public function encode(\DOMDocument $document, $formatOutput = null, $preserveWhiteSpace = null)
    {
        if (null !== $formatOutput) {
            $document->formatOutput = $formatOutput;
        }
        if (null === $preserveWhiteSpace) {
            $document->preserveWhiteSpace = $preserveWhiteSpace;
        }

        return $this->embellishes($document->saveXml());
    }

    /**
     * @param string    $data
     * @param bool|null $formatOutput
     * @param bool|null $preserveWhiteSpace
     *
     * @return \DOMDocument
     */
    public function decode($data, $formatOutput = null, $preserveWhiteSpace = null, $loadOptions = null)
    {
        $document = new \DOMDocument();
        if (null !== $formatOutput) {
            $document->formatOutput = $formatOutput;
        }
        if (null === $preserveWhiteSpace) {
            $document->preserveWhiteSpace = $preserveWhiteSpace;
        }
        $document->loadXML($data, $loadOptions);
        if ($error = libxml_get_last_error()) {
            libxml_clear_errors();

            throw new UnexpectedValueException($error->message);
        }

        return $document;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function embellishes($content)
    {
        // Align configuration attributes for better readability
        $match = [];
        preg_match(self::CONFIGURATION_ATTRIBUTE_PATTERN, $content, $match);
        if (isset($match[1])) {
            $oldOptions = $options = $match[1];
            preg_match_all('/(?:\s|\t)+([^=>]+="[^">]*")/', $options, $match);
            $options = sprintf(
                "%s\n",
                implode("\n  ", $match[1])
            );
            $content = str_replace(
                $oldOptions,
                sprintf(' %s', $options),
                $content
            );
        }

        return $content;
    }
}

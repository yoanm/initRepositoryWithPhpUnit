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
        var_dump("ENCODE");
        var_dump([$document->formatOutput, $document->preserveWhiteSpace]);
        if (null !== $formatOutput) {
            $document->formatOutput = $formatOutput;
        }
        if (null === $preserveWhiteSpace) {
            $document->preserveWhiteSpace = $preserveWhiteSpace;
        }
        var_dump("ENCODE - AFTER");
        var_dump([$document->formatOutput, $document->preserveWhiteSpace]);

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
        var_dump("DECODE");
        var_dump([$document->formatOutput, $document->preserveWhiteSpace]);
        if (null !== $formatOutput) {
            $document->formatOutput = $formatOutput;
        }
        if (null === $preserveWhiteSpace) {
            $document->preserveWhiteSpace = $preserveWhiteSpace;
        }
        var_dump("DECODE - AFTER");
        var_dump([$document->formatOutput, $document->preserveWhiteSpace]);
        $document->loadXML($data, $loadOptions);
        var_dump("DECODE - AFTER 2");
        var_dump([$document->formatOutput, $document->preserveWhiteSpace]);
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

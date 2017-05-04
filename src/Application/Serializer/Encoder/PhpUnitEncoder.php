<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class PhpUnitEncoder
{
    /**
     * @param mixed $data
     *
     * @return string
     */
    public function encode($data)
    {
        $encodedJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnexpectedValueException(json_last_error_msg());
        } else {
            $encodedJson .= "\n";
        }

        return $encodedJson;
    }

    /**
     * @param string $data
     *
     * @return mixed
     */
    public function decode($data)
    {
        $decoded = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnexpectedValueException(json_last_error_msg());
        }

        return $decoded;
    }
}

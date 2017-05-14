<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

interface DenormalizerInterface
{
    /**
     * @param \DOMNode $node
     *
     * @return mixed
     */
    public function denormalize(\DOMNode $node);

    /**
     * @param \DomNode $node
     *
     * @return bool
     */
    public function supportsDenormalization(\DomNode $node);
}

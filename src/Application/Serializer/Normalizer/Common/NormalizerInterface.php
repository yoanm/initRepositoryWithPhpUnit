<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

interface NormalizerInterface
{
    /**
     * @param ConfigurationItemInterface $item
     * @param \DOMDocument               $document
     *
     * @return \DOMNode
     */
    public function normalize($item, \DOMDocument $document);

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function supportsNormalization($item);
}

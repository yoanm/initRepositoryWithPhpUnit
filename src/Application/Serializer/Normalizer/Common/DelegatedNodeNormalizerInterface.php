<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

interface DelegatedNodeNormalizerInterface
{
    /**
     * @param mixed $item
     *
     * @return NormalizerInterface
     *
     * @throws \Exception
     */
    public function getNormalizer($item);

    /**
     * @param \DOMNode $domNode
     *
     * @return DenormalizerInterface
     *
     * @throws \Exception
     */
    public function getDenormalizer(\DOMNode $domNode);
}

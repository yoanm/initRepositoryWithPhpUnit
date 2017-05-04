<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

interface DenormalizerInterface
{
    /**
     * @param array $list
     *
     * @return mixed
     */
    public function denormalize(array $list);
}

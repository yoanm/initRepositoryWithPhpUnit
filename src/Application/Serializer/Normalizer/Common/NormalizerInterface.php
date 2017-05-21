<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Application\Serializer\NormalizedNode;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

interface NormalizerInterface
{
    /**
     * @param ConfigurationItemInterface $item
     *
     * @return NormalizedNode
     */
    public function normalize($item);

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function supportsNormalization($item);
}

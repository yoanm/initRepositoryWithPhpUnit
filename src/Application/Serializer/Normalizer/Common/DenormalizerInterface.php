<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

interface DenormalizerInterface
{
    /**
     * @param \DOMNode $node
     *
     * @return ConfigurationItemInterface
     */
    public function denormalize(\DOMNode $node);

    /**
     * @param \DomNode $node
     *
     * @return bool
     */
    public function supportsDenormalization(\DomNode $node);
}

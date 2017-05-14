<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Logging;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;

class LogUpdater extends AbstractNodeUpdater
{
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater $attributeUpdater
     */
    public function __construct(
        AttributeUpdater $attributeUpdater
    ) {
        parent::__construct();
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param Log $baseItem
     * @param Log $newItem
     *
     * @return Log
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Log(
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
        return $item instanceof Log;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        $isSupported = $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $this->extractType($newItem) === $this->extractType($baseItem);

        return $isSupported;
    }

    protected function extractType(Log $log)
    {
        foreach ($log->getAttributeList() as $attribute) {
            if ('type' === $attribute->getName()) {
                return $attribute->getValue();
            }
        }

        return null;
    }
}

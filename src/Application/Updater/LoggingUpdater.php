<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Logging\LogUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;

class LoggingUpdater extends AbstractNodeUpdater
{
    /**
     * @param LogUpdater $logUpdater
     */
    public function __construct(
        LogUpdater $logUpdater
    ) {
        parent::__construct([$logUpdater]);
    }

    /**
     * @param Logging $baseItem
     * @param Logging $newItem
     *
     * @return Logging
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Logging($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
        return $item instanceof Logging;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

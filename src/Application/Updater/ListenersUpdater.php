<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Listeners\ListenerUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;

class ListenersUpdater extends AbstractNodeUpdater
{
    /**
     * @param ListenerUpdater $listenerUpdater
     */
    public function __construct(
        ListenerUpdater $listenerUpdater
    ) {
        parent::__construct([$listenerUpdater]);
    }

    /**
     * @param Listeners $baseItem
     * @param Listeners $newItem
     *
     * @return Listeners
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Listeners($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof Listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

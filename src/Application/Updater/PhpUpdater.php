<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Php\PhpItemUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;

class PhpUpdater extends AbstractNodeUpdater
{
    /**
     * @param PhpItemUpdater $phpItemUpdater
     */
    public function __construct(
        PhpItemUpdater $phpItemUpdater
    ) {
        parent::__construct([$phpItemUpdater]);
    }

    /**
     * @param Php $baseItem
     * @param Php $newItem
     *
     * @return Php
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Php($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
        return $item instanceof Php;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

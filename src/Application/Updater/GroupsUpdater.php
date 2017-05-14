<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Groups\GroupInclusionUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

class GroupsUpdater extends AbstractNodeUpdater
{
    /**
     * @param GroupInclusionUpdater $groupInclusionUpdater
     */
    public function __construct(
        GroupInclusionUpdater $groupInclusionUpdater
    ) {
        parent::__construct([$groupInclusionUpdater]);
    }

    /**
     * @param Groups $baseItem
     * @param Groups $newItem
     *
     * @return Groups
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Groups($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof Groups;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

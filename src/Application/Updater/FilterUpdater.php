<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Filter\WhiteListUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

class FilterUpdater extends AbstractNodeUpdater
{
    /**
     * @param WhiteListUpdater $groupInclusionUpdater
     */
    public function __construct(
        WhiteListUpdater $groupInclusionUpdater
    ) {
        parent::__construct([$groupInclusionUpdater]);
    }

    /**
     * @param Filter $baseItem
     * @param Filter $newItem
     *
     * @return Filter
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Filter($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof Filter;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

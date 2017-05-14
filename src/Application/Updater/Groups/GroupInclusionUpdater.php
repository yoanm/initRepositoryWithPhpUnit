<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Groups;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class GroupInclusionUpdater extends AbstractNodeUpdater
{
    /**
     * @param GroupItemUpdater   $groupItemUpdater
     * @param HeaderFooterHelper $headerFooterHelper
     */
    public function __construct(
        GroupItemUpdater $groupItemUpdater,
        HeaderFooterHelper $headerFooterHelper
    ) {
        parent::__construct($headerFooterHelper, [$groupItemUpdater]);
    }

    /**
     * @param GroupInclusion $baseItem
     * @param GroupInclusion $newItem
     *
     * @return GroupInclusion
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new GroupInclusion(
            $this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()),
            $baseItem->isExcluded()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof GroupInclusion;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $baseItem->isExcluded() === $newItem->isExcluded()
        ;
    }
}

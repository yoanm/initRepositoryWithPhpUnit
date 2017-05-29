<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Groups;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class GroupInclusionUpdater extends AbstractNodeUpdater
{
    /**
     * @param GroupItemUpdater   $groupItemUpdater
     * @param NodeUpdaterHelper $nodeUpdaterHelper
     */
    public function __construct(
        GroupItemUpdater $groupItemUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$groupItemUpdater]);
    }

    /**
     * @param GroupInclusion $baseItem
     * @param GroupInclusion $newItem
     *
     * @return GroupInclusion
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new GroupInclusion(
            $this->getNodeUpdaterHelper()->mergeBlockList(
                $baseItem->getBlockList(),
                $newItem->getBlockList(),
                $this
            ),
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

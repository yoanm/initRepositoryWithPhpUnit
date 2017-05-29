<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Groups\GroupInclusionUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

class GroupsUpdater extends AbstractNodeUpdater
{
    /**
     * @param GroupInclusionUpdater $groupInclusionUpdater
     * @param NodeUpdaterHelper $nodeUpdaterHelper
     */
    public function __construct(
        GroupInclusionUpdater $groupInclusionUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$groupInclusionUpdater]);
    }

    /**
     * @param Groups $baseItem
     * @param Groups $newItem
     *
     * @return Groups
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Groups(
            $this->getNodeUpdaterHelper()->mergeBlockList(
                $baseItem->getBlockList(),
                $newItem->getBlockList(),
                $this
            )
        );
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

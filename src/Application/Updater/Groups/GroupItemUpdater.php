<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Groups;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;

class GroupItemUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;

    /**
     * @param PlainValueUpdater $plainValueUpdater
     */
    public function __construct(
        PlainValueUpdater $plainValueUpdater
    ) {
        parent::__construct();
        $this->plainValueUpdater = $plainValueUpdater;
    }

    /**
     * @param Group $baseItem
     * @param Group $newItem
     *
     * @return Group
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $baseItem;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
        return $item instanceof Group;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        $isSupported = $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $newItem->getValue() === $baseItem->getValue();

        return $isSupported;
    }
}

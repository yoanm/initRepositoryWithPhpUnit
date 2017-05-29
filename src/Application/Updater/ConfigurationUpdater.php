<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

class ConfigurationUpdater extends AbstractNodeUpdater
{
    /**
     * @param AttributeUpdater  $attributeUpdater
     * @param TestSuitesUpdater $testSuitesUpdater
     * @param GroupsUpdater     $groupsUpdater
     * @param FilterUpdater     $filterUpdater
     * @param LoggingUpdater    $loggingUpdater
     * @param ListenersUpdater  $listenersUpdater
     * @param PhpUpdater        $phpUpdater
     * @param NodeUpdaterHelper $nodeUpdaterHelper
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        TestSuitesUpdater $testSuitesUpdater,
        GroupsUpdater $groupsUpdater,
        FilterUpdater $filterUpdater,
        LoggingUpdater $loggingUpdater,
        ListenersUpdater $listenersUpdater,
        PhpUpdater $phpUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct(
            $nodeUpdaterHelper,
            [
                $testSuitesUpdater,
                $groupsUpdater,
                $filterUpdater,
                $loggingUpdater,
                $listenersUpdater,
                $phpUpdater,
            ]
        );
        $this->attributeUpdater = $attributeUpdater;
    }

    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param Configuration $baseItem
     * @param Configuration $newItem
     *
     * @return Configuration
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Configuration(
            $this->attributeUpdater->update(
                $baseItem->getAttributeList(),
                $newItem->getAttributeList()
            ),
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
        return $item instanceof Configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

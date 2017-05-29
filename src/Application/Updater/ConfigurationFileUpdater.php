<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;

    /**
     * @param PlainValueUpdater    $plainValueUpdater
     * @param ConfigurationUpdater $configurationUpdater
     * @param NodeUpdaterHelper    $nodeUpdaterHelper
     */
    public function __construct(
        PlainValueUpdater $plainValueUpdater,
        ConfigurationUpdater $configurationUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$configurationUpdater]);
        $this->plainValueUpdater = $plainValueUpdater;
    }

    /**
     * @param ConfigurationFile $baseItem
     * @param ConfigurationFile $newItem
     *
     * @return ConfigurationFile
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new ConfigurationFile(
            $this->plainValueUpdater->update($baseItem->getVersion(), $newItem->getVersion()),
            $this->plainValueUpdater->update($baseItem->getEncoding(), $newItem->getEncoding()),
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
        return $item instanceof ConfigurationFile;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

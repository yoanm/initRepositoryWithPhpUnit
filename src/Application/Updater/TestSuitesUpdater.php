<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\TestSuites\TestSuiteUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

class TestSuitesUpdater extends AbstractNodeUpdater
{
    /**
     * @param TestSuiteUpdater $testSuiteUpdater
     */
    public function __construct(
        TestSuiteUpdater $testSuiteUpdater
    ) {
        parent::__construct([$testSuiteUpdater]);
    }

    /**
     * @param TestSuites $baseItem
     * @param TestSuites $newItem
     *
     * @return TestSuites
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new TestSuites($this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof TestSuites;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

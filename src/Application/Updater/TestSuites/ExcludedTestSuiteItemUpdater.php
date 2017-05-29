<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\TestSuites;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;

class ExcludedTestSuiteItemUpdater extends AbstractNodeUpdater
{
    /**
     * @param ExcludedTestSuiteItem $baseItem
     * @param ExcludedTestSuiteItem $newItem
     *
     * @return ExcludedTestSuiteItem
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new ExcludedTestSuiteItem($baseItem->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof ExcludedTestSuiteItem;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $newItem->getValue() === $baseItem->getValue()
        ;
    }
}

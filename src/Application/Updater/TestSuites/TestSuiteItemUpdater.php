<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\TestSuites;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItemInterface;

class TestSuiteItemUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater $attributeUpdater
     * @param PlainValueUpdater $plainValueUpdater
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        PlainValueUpdater $plainValueUpdater
    ) {
        parent::__construct();
        $this->plainValueUpdater = $plainValueUpdater;
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param TestSuiteItem $baseItem
     * @param TestSuiteItem $newItem
     *
     * @return TestSuiteItem
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        if ($baseItem instanceof ExcludedTestSuiteItem && $newItem instanceof ExcludedTestSuiteItem) {
            return $baseItem;
        }
        return new TestSuiteItem(
            $baseItem->getType(),
            $baseItem->getValue(),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof TestSuiteItemInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && (
                (
                    $newItem instanceof TestSuiteItem
                    && $newItem->getType() === $baseItem->getType()
                    && $newItem->getValue() === $baseItem->getValue()
                )
                ||
                (
                    $newItem instanceof ExcludedTestSuiteItem
                    && $newItem->getValue() === $baseItem->getValue()
                )
            )
        ;
    }
}

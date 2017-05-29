<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\TestSuites;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class TestSuiteUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater             $attributeUpdater
     * @param PlainValueUpdater            $plainValueUpdater
     * @param TestSuiteItemUpdater         $testSuiteItemUpdater
     * @param ExcludedTestSuiteItemUpdater $excludedTestSuiteItemUpdater
     * @param NodeUpdaterHelper            $nodeUpdaterHelper
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        PlainValueUpdater $plainValueUpdater,
        TestSuiteItemUpdater $testSuiteItemUpdater,
        ExcludedTestSuiteItemUpdater $excludedTestSuiteItemUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$testSuiteItemUpdater, $excludedTestSuiteItemUpdater]);
        $this->plainValueUpdater = $plainValueUpdater;
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param TestSuite $baseItem
     * @param TestSuite $newItem
     *
     * @return TestSuite
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new TestSuite(
            $baseItem->getName(),
            $this->getNodeUpdaterHelper()->mergeBlockList(
                $baseItem->getBlockList(),
                $newItem->getBlockList(),
                $this
            ),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof TestSuite;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $baseItem->getName() === $newItem->getName()
        ;
    }
}

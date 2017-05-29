<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Filter;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\NodeUpdaterHelper;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;

class ExcludedWhiteListUpdater extends AbstractNodeUpdater
{
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater     $attributeUpdater
     * @param WhiteListItemUpdater $whiteListItemUpdater
     * @param NodeUpdaterHelper    $nodeUpdaterHelper
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        WhiteListItemUpdater $whiteListItemUpdater,
        NodeUpdaterHelper $nodeUpdaterHelper
    ) {
        parent::__construct($nodeUpdaterHelper, [$whiteListItemUpdater]);
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param ExcludedWhiteList $baseItem
     * @param ExcludedWhiteList $newItem
     *
     * @return ExcludedWhiteList
     */
    public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new ExcludedWhiteList(
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList()),
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
        return $item instanceof ExcludedWhiteList;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem) && get_class($baseItem) === get_class($newItem);
    }
}

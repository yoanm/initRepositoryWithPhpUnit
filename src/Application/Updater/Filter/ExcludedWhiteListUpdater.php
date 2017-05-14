<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Filter;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;

class ExcludedWhiteListUpdater extends AbstractNodeUpdater
{
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater     $attributeUpdater
     * @param WhiteListItemUpdater $whiteListItemUpdater
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        WhiteListItemUpdater $whiteListItemUpdater
    ) {
        parent::__construct([$whiteListItemUpdater]);
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param ExcludedWhiteList $baseItem
     * @param ExcludedWhiteList $newItem
     *
     * @return ExcludedWhiteList
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new ExcludedWhiteList(
            $this->mergeItemList($baseItem->getItemList(), $newItem->getItemList()),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
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

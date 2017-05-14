<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;

class AttributeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;

    /**
     * @param PlainValueUpdater $plainValueUpdater
     */
    public function __construct(PlainValueUpdater $plainValueUpdater)
    {
        $this->plainValueUpdater = $plainValueUpdater;
    }

    /**
     * @param Attribute[] $baseItemList
     * @param Attribute[] $newItemList
     *
     * @return Attribute[]
     */
    public function update(array $baseItemList, array $newItemList)
    {
        $updatedItemList = [];
        /** @var Attribute $baseItem */
        while ($baseItem = array_shift($baseItemList)) {
            $item = $baseItem;
            foreach ($newItemList as $newItemKey => $newItem) {
                if ($newItem->getName() == $baseItem->getName()) {
                    $item = $this->mergeItem($baseItem, $newItem);
                    unset($newItemList[$newItemKey]);
                }
            }
            $updatedItemList[] = $item;
        }
        // Append remaining items
        foreach ($newItemList as $newItem) {
            $updatedItemList[] = $newItem;
        }

        return $updatedItemList;
    }

    protected function mergeItem(Attribute $baseItem, Attribute $newItem)
    {
        return new Attribute(
            $baseItem->getName(),
            $this->plainValueUpdater->update($newItem->getValue(), $baseItem->getValue())
        );
    }
}

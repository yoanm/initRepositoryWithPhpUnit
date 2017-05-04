<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationItemInterface;

class ListUpdater
{
    /**
     * @param ConfigurationItemInterface[] $newEntityList
     * @param ConfigurationItemInterface[] $oldEntityList
     *
     * @return ConfigurationItemInterface[]
     */
    public function update(array $newEntityList, array $oldEntityList)
    {
        $existingEntityIdList = [];
        foreach ($newEntityList as $entity) {
            $existingEntityIdList[$entity->getItemId()] = true;
        }
        $normalizedOldEntityList = [];
        foreach ($oldEntityList as $oldEntity) {
            if (!array_key_exists($oldEntity->getItemId(), $existingEntityIdList)) {
                $normalizedOldEntityList[] = $oldEntity;
            } else {
                // A new entity have been defined, loop over new entity list and append all entities with the same id
                $oldEntityId = $oldEntity->getItemId();
                foreach ($newEntityList as $newEntityKey => $newEntity) {
                    if ($newEntity->getItemId() == $oldEntityId) {
                        $normalizedOldEntityList[] = $newEntity;
                        unset($newEntityList[$newEntityKey]);
                    }
                }
            }
        }

        return array_merge($normalizedOldEntityList, $newEntityList);
    }

    /**
     * @param array $newPropertyList
     * @param array $oldPropertyList
     *
     * @return array
     */
    public function updateRaw(array $newPropertyList, array $oldPropertyList)
    {
        $newPropertyKeyList = [];
        foreach ($newPropertyList as $propertyKey => $value) {
            $newPropertyKeyList[$propertyKey] = true;
        }
        $normalizedOldPropertyList = [];
        foreach ($oldPropertyList as $propertyKey => $oldPropertyValue) {
            if (!isset($newPropertyKeyList[$propertyKey])) {
                if (count($oldPropertyValue)) {
                    $normalizedOldPropertyList[$propertyKey] = $oldPropertyValue;
                }
            } else {
                // A new value have been defined
                $newValue = $this->mergeRawValue(
                    $newPropertyList[$propertyKey],
                    $oldPropertyValue
                );
                unset($newPropertyList[$propertyKey]);
                if (count($newValue)) {
                    $normalizedOldPropertyList[$propertyKey] = $newValue;
                }
            }
        }

        return array_merge($normalizedOldPropertyList, $newPropertyList);
    }

    protected function mergeRawValue($newPropertyValue, $oldPropertyValue)
    {
        $isArray = is_array($newPropertyValue) && is_array($oldPropertyValue);

        return (false === $isArray)
            ? $newPropertyValue
            : $this->updateRaw($newPropertyValue, $oldPropertyValue)
        ;
    }
}

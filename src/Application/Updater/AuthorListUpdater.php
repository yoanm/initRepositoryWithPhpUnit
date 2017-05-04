<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

use Yoanm\PhpUnitConfigManager\Domain\Model\Author;

class AuthorListUpdater
{
    /**
     * @param Author[] $newEntityList
     * @param Author[] $oldEntityList
     *
     * @return Author[]
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
                        $normalizedOldEntityList[] = $this->mergeAuthor($newEntity, $oldEntity);
                        unset($newEntityList[$newEntityKey]);
                    }
                }
            }
        }

        return array_merge($normalizedOldEntityList, $newEntityList);
    }

    /**
     * @param Author $newEntity
     * @param Author $oldEntity
     * @return Author
     */
    protected function mergeAuthor(Author $newEntity, Author $oldEntity)
    {
        return new Author(
            $newEntity->getName(),
            $newEntity->getEmail() ? $newEntity->getEmail() : $oldEntity->getEmail(),
            $newEntity->getRole() ? $newEntity->getRole() : $oldEntity->getRole()
        );
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

class KeywordListUpdater
{
    /**
     * @param string[] $newList
     * @param string[] $oldList
     *
     * @return string[]
     */
    public function update(array $oldList, array $newList)
    {
        return array_values(
            array_unique(
                array_merge($newList, $oldList)
            )
        );
    }
}

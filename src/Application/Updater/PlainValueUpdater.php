<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater;

class PlainValueUpdater
{
    /**
     * @param string $baseValue
     * @param string $newValue
     *
     * @return string
     */
    public function update($newValue, $baseValue)
    {
        return $newValue ? $newValue : $baseValue;
    }
}

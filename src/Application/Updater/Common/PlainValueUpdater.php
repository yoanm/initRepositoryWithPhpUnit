<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

class PlainValueUpdater
{
    /**
     * @param string|null $newValue
     * @param string|null $baseValue
     *
     * @return string|null
     */
    public function update($baseValue, $newValue)
    {
        return $newValue ? $newValue : $baseValue;
    }
}

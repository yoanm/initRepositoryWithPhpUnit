<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

interface DelegatedNodeUpdaterInterface
{
    /**
     * @param ConfigurationItemInterface $item
     *
     * @return AbstractNodeUpdater|null
     *
     * @throws \Exception
     */
    public function getUpdater(ConfigurationItemInterface $item, $throwException = true);
}

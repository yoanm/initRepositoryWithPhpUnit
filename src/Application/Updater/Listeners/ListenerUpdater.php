<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Listeners;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\HeaderFooterHelper;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;

class ListenerUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;

    /**
     * @param PlainValueUpdater  $plainValueUpdater
     * @param HeaderFooterHelper $headerFooterHelper
     */
    public function __construct(
        PlainValueUpdater $plainValueUpdater,
        HeaderFooterHelper $headerFooterHelper
    ) {
        parent::__construct($headerFooterHelper);
        $this->plainValueUpdater = $plainValueUpdater;
    }

    /**
     * @param Listener $baseItem
     * @param Listener $newItem
     *
     * @return Listener
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new Listener(
            $baseItem->getClass(),
            $this->plainValueUpdater->update($newItem->getFile(), $baseItem->getFile()),
            $this->mergeItemList($baseItem->getItemList(), $newItem->getItemList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item)
    {
        return $item instanceof Listener;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        $isSupported = $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $newItem->getClass() === $baseItem->getClass();

        return $isSupported;
    }
}

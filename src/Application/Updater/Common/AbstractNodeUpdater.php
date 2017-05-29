<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

abstract class AbstractNodeUpdater implements DelegatedNodeUpdaterInterface
{
    /** @var AbstractNodeUpdater[] */
    private $updateDelegateList;
    /** @var NodeUpdaterHelper */
    private $nodeUpdaterHelper;

    /**
     * @param NodeUpdaterHelper     $nodeUpdaterHelper
     * @param AbstractNodeUpdater[] $updaterDelegateList
     */
    public function __construct(NodeUpdaterHelper $nodeUpdaterHelper, array $updaterDelegateList = [])
    {
        $this->updateDelegateList = $updaterDelegateList;
        $this->nodeUpdaterHelper = $nodeUpdaterHelper;
    }

    /**
     * @return NodeUpdaterHelper
     */
    public function getNodeUpdaterHelper()
    {
        return $this->nodeUpdaterHelper;
    }

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return ConfigurationItemInterface
     */
    abstract public function update(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return bool
     */
    abstract public function supports(ConfigurationItemInterface $item);

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return bool
     */
    abstract public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return AbstractNodeUpdater|null
     *
     * @throws \Exception
     */
    public function getUpdater(ConfigurationItemInterface $item, $throwException = true)
    {
        foreach ($this->updateDelegateList as $delegate) {
            if ($delegate->supports($item)) {
                return $delegate;
            }
        }

        if (true !== $throwException) {
            return null;
        }

        throw new \Exception(sprintf(
            'No update found for item %s',
            get_class($item)
        ));
    }
}

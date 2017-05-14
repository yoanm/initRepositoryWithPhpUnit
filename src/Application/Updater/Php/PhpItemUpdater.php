<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Php;

use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AbstractNodeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\AttributeUpdater;
use Yoanm\PhpUnitConfigManager\Application\Updater\Common\PlainValueUpdater;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpItemUpdater extends AbstractNodeUpdater
{
    /** @var PlainValueUpdater */
    private $plainValueUpdater;
    /** @var AttributeUpdater */
    private $attributeUpdater;

    /**
     * @param AttributeUpdater $attributeUpdater
     * @param PlainValueUpdater $plainValueUpdater
     */
    public function __construct(
        AttributeUpdater $attributeUpdater,
        PlainValueUpdater $plainValueUpdater
    ) {
        parent::__construct();
        $this->plainValueUpdater = $plainValueUpdater;
        $this->attributeUpdater = $attributeUpdater;
    }

    /**
     * @param PhpItem $baseItem
     * @param PhpItem $newItem
     *
     * @return PhpItem
     */
    public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return new PhpItem(
            $baseItem->getName(),
            $this->plainValueUpdater->update($newItem->getValue(), $baseItem->getValue()),
            $this->attributeUpdater->update($baseItem->getAttributeList(), $newItem->getAttributeList())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ConfigurationItemInterface $item) {
        return $item instanceof PhpItem;
    }

    /**
     * {@inheritdoc}
     */
    public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        return $this->supports($newItem)
            && get_class($baseItem) === get_class($newItem)
            && $baseItem->getName() === $newItem->getName()
            && $this->hasSharedAttribute($baseItem, $newItem)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function hasSharedAttribute(PhpItem $baseItem, PhpItem $newItem)
    {
        $baseAttributeName = null;
        $newAttributeName = null;
        foreach ($baseItem->getAttributeList() as $attribute) {
            if ('name' === $attribute->getName()) {
                $baseAttributeName = $attribute->getValue();
                break;
            }
        }
        foreach ($newItem->getAttributeList() as $attribute) {
            if ('name' === $attribute->getName()) {
                $newAttributeName = $attribute->getValue();
                break;
            }
        }

        return $newAttributeName === $baseAttributeName;
    }
}

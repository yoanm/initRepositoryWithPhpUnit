<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class ExcludedWhiteList extends AttributeContainer
{
    /** @var ConfigurationItemInterface[]|WhiteListItem[] */
    private $itemList;

    /**
     * @param ConfigurationItemInterface[]|WhiteListItem[] $itemList
     * @param Attribute[]     $attributeList
     */
    public function __construct(array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->itemList = $itemList;
    }

    /**
     * @return ConfigurationItemInterface[]|WhiteListItem[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

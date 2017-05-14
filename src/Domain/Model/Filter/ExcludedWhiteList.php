<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class ExcludedWhiteList extends AttributeContainer
{
    /** @var WhiteListItem[]|UnmanagedNode[] */
    private $itemList;

    /**
     * @param WhiteListItem[]|UnmanagedNode[] $itemList
     * @param Attribute[]     $attributeList
     */
    public function __construct(array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->itemList = $itemList;
    }

    /**
     * @return WhiteListItem[]|UnmanagedNode[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

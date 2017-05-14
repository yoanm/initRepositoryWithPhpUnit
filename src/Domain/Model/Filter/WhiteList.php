<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class WhiteList extends AttributeContainer
{
    /** @var WhiteListItem[]|ExcludedWhiteList[]|UnmanagedNode[]
    private $itemList;

    /**
     * @param WhiteListItem[]|ExcludedWhiteList[]|UnmanagedNode[] $item
     * @param Attribute[]                                             $attributeList
     */
    public function __construct(array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->itemList = $itemList;
    }

    /**
     * @return WhiteListItem[]|ExcludedWhiteList[]|UnmanagedNode[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

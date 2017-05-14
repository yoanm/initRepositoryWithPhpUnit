<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Filter;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;

class WhiteList extends AttributeContainer
{
    /** @var WhiteListItem[]|ExcludedWhiteList[] */
    private $itemList;

    /**
     * @param WhiteListItem[]|ExcludedWhiteList[] $itemList
     * @param Attribute[]                         $attributeList
     */
    public function __construct(array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->itemList = $itemList;
    }

    /**
     * @return WhiteListItem[]|ExcludedWhiteList[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

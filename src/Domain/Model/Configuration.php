<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\AttributeContainer;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class Configuration extends AttributeContainer
{
    /** @var ConfigurationItemInterface[]|TestSuites[]|Groups[]|Filter[]|Logging[]|Listeners[]|Php[] */
    private $itemList;

    /**
     * @param ConfigurationItemInterface[] $itemList
     * @param Attribute[]                                  $attributeList
     */
    public function __construct(array $itemList = [], array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->itemList = $itemList;
    }

    /**
     * @return ConfigurationItemInterface[]|TestSuites[]|Groups[]|Filter[]|Logging[]|Listeners[]|Php[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

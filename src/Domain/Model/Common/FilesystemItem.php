<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class FilesystemItem extends AttributeContainer implements ConfigurationItemInterface
{
    const TYPE_FILE = 1;
    const TYPE_DIRECTORY = 2;

    /** @var integer */
    private $type;
    /** @var string */
    private $value;

    /**
     * @param string      $type
     * @param string      $value
     * @param Attribute[] $attributeList
     */
    public function __construct($type, $value, array $attributeList = [])
    {
        parent::__construct($attributeList);
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

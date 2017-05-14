<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class Attribute implements ConfigurationItemInterface
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;

    /**
     * @param string      $name
     * @param string|null $value
     */
    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

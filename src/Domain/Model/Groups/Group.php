<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Groups;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class Group implements ConfigurationItemInterface
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

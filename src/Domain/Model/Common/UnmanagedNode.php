<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class UnmanagedNode implements ConfigurationItemInterface
{
    /** @var \DOMNode */
    private $value;

    /**
     * @param \DOMNode $value
     */
    public function __construct(\DOMNode $value)
    {
        $this->value = $value;
    }

    /**
     * @return \DOMNode
     */
    public function getValue()
    {
        return $this->value;
    }
}

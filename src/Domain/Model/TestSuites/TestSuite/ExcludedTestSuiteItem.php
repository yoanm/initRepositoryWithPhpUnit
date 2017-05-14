<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

class ExcludedTestSuiteItem implements TestSuiteItemInterface
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

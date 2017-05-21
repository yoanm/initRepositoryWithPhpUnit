<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class ExcludedTestSuiteItem extends Node implements TestSuiteItemInterface
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct();
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

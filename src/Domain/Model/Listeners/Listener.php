<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class Listener extends Node implements ConfigurationItemInterface
{
    /** @var string */
    private $class;
    /** @var string|null */
    private $file;

    /**
     * @param string      $class
     * @param string|null $file
     * @param Block[]     $itemList
     */
    public function __construct($class, $file = null, array $itemList = [])
    {
        parent::__construct($itemList);
        $this->class = $class;
        $this->file = $file;
    }
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return null|string
     */
    public function getFile()
    {
        return $this->file;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class Listener implements ConfigurationItemInterface
{
    /** @var string */
    private $class;
    /** @var string|null */
    private $file;
    /** @var UnmanagedNode[] */
    private $itemList;

    /**
     * @param string          $class
     * @param string|null     $file
     * @param UnmanagedNode[] $itemList
     */
    public function __construct($class, $file = null, array $itemList = [])
    {
        $this->class = $class;
        $this->file = $file;
        $this->itemList = $itemList;
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

    /**
     * @return UnmanagedNode[]
     */
    public function getItemList()
    {
        return $this->itemList;
    }
}

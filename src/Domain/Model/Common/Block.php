<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model\Common;

class Block
{
    /** @var ConfigurationItemInterface[] */
    private $headerNodeList = null;
    /** @var UnmanagedNode[] */
    private $footerNodeList = [];
    /** @var UnmanagedNode */
    private $item;

    /**
     * @param ConfigurationItemInterface $item
     * @param UnmanagedNode[]            $headerNodeList
     * @param UnmanagedNode[]            $footerNodeList
     */
    public function __construct(
        ConfigurationItemInterface $item,
        array $headerNodeList = [],
        array $footerNodeList = []
    ) {
        $this->item = $item;
        $this->headerNodeList = $headerNodeList;
        $this->footerNodeList = $footerNodeList;
    }

    /**
     * @return UnmanagedNode[]
     */
    public function getHeaderNodeList()
    {
        return $this->headerNodeList;
    }

    /**
     * @return UnmanagedNode[]
     */
    public function getFooterNodeList()
    {
        return $this->footerNodeList;
    }

    /**
     * @return ConfigurationItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class Block
{
    /** @var ConfigurationItemInterface[] */
    private $headerNodeList = null;
    /** @var ConfigurationItemInterface[] */
    private $footerNodeList = [];
    /** @var ConfigurationItemInterface */
    private $item;

    /**
     * @param ConfigurationItemInterface   $item
     * @param ConfigurationItemInterface[] $headerNodeList
     * @param ConfigurationItemInterface[] $footerNodeList
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
     * @return ConfigurationItemInterface[]
     */
    public function getHeaderNodeList()
    {
        return $this->headerNodeList;
    }

    /**
     * @return ConfigurationItemInterface[]
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

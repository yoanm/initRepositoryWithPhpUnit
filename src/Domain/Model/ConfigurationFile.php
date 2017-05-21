<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Node;

class ConfigurationFile extends Node implements ConfigurationItemInterface
{
    const FILENAME = 'phpunit.xml.dist';

    /** @var string */
    private $version;
    /** @var string */
    private $encoding;

    /**
     * @param string  $version
     * @param string  $encoding
     * @param Block[] $itemList
     */
    public function __construct($version, $encoding, array $itemList = [])
    {
        parent::__construct($itemList);
        $this->version = $version;
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
}

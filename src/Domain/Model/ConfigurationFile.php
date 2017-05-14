<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;

class ConfigurationFile implements ConfigurationItemInterface
{
    const FILENAME = 'phpunit.xml.dist';

    /** @var string */
    private $version;
    /** @var string */
    private $encoding;
    /** @var ConfigurationItemInterface[]|Configuration[] */
    private $nodeList = [];

    /**
     * @param string          $version
     * @param string          $encoding
     * @param ConfigurationItemInterface[]|Configuration[] $nodeList
     */
    public function __construct($version, $encoding, array $nodeList = [])
    {
        $this->version = $version;
        $this->encoding = $encoding;
        $this->nodeList = $nodeList;
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

    /**
     * @return ConfigurationItemInterface[]|Configuration[]
     */
    public function getNodeList()
    {
        return $this->nodeList;
    }
}

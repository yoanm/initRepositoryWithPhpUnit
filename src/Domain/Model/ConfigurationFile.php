<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class ConfigurationFile
{
    const KEY_NAME = 'name';
    const KEY_TYPE = 'type';
    const KEY_LICENSE = 'license';
    const KEY_VERSION = 'version';
    const KEY_DESCRIPTION = 'description';
    const KEY_KEYWORDS = 'keywords';
    const KEY_AUTHORS = 'authors';
    const KEY_PROVIDE = 'provide';
    const KEY_SUGGEST = 'suggest';
    const KEY_SUPPORT = 'support';
    const KEY_REQUIRE = 'require';
    const KEY_REQUIRE_DEV = 'require-dev';
    const KEY_AUTOLOAD = 'autoload';
    const KEY_AUTOLOAD_DEV = 'autoload-dev';
    const KEY_SCRIPTS = 'scripts';

    /** @var Configuration */
    private $configuration;
    /** @var string[] */
    private $keyList = [];

    /**
     * @param Configuration $configuration
     * @param string[]      $keyList
     */
    public function __construct(Configuration $configuration, array $keyList)
    {
        $this->configuration = $configuration;
        $this->keyList = $keyList;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return \string[]
     */
    public function getKeyList()
    {
        return $this->keyList;
    }
}

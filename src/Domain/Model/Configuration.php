<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class Configuration
{
    /** @var string */
    private $packageName;
    /** @var string */
    private $type;
    /** @var string */
    private $license;
    /** @var string */
    private $packageVersion;
    /** @var string */
    private $description;
    /** @var string[] */
    private $keywordList = [];
    /** @var Author[] */
    private $authorList = [];
    /** @var Package[] */
    private $providedPackageList = [];
    /** @var SuggestedPackage[] */
    private $suggestedPackageList = [];
    /** @var Support[] */
    private $supportList = [];
    /** @var Autoload[] */
    private $autoloadList = [];
    /** @var Autoload[] */
    private $autoloadDevList = [];
    /** @var Package[] */
    private $requiredPackageList = [];
    /** @var Package[] */
    private $requiredDevPackageList = [];
    /** @var Script[] */
    private $scriptList = [];
    /** @var array */
    private $unmanagedPropertyList = [];

    /**
     * @param string|null        $packageName
     * @param string|null        $type
     * @param string|null        $license
     * @param string|null        $packageVersion
     * @param string|null        $description
     * @param string[]           $keywordList
     * @param Author[]           $authorList
     * @param Package[]          $providedPackageList
     * @param SuggestedPackage[] $suggestedPackageList
     * @param Support[]          $supportList
     * @param Autoload[]         $autoloadList
     * @param Autoload[]         $autoloadDevList
     * @param Package[]          $requiredPackageList
     * @param Package[]          $requiredDevPackageList
     * @param Script[]           $scriptList
     * @param array              $unmanagedPropertyList
     */
    public function __construct(
        $packageName,
        $type,
        $license,
        $packageVersion,
        $description,
        array $keywordList,
        array $authorList,
        array $providedPackageList,
        array $suggestedPackageList,
        array $supportList,
        array $autoloadList,
        array $autoloadDevList,
        array $requiredPackageList,
        array $requiredDevPackageList,
        array $scriptList,
        array $unmanagedPropertyList = []
    ) {
        $this->packageName = $packageName;
        $this->type = $type;
        $this->description = $description;
        $this->license = $license;
        $this->packageVersion = $packageVersion;

        $this->keywordList = $keywordList;
        $this->authorList = $authorList;
        $this->providedPackageList = $providedPackageList;
        $this->suggestedPackageList = $suggestedPackageList;
        $this->supportList = $supportList;
        $this->autoloadList = $autoloadList;
        $this->autoloadDevList = $autoloadDevList;
        $this->requiredPackageList = $requiredPackageList;
        $this->requiredPackageList = $requiredPackageList;
        $this->requiredDevPackageList = $requiredDevPackageList;
        $this->scriptList = $scriptList;
        $this->unmanagedPropertyList = $unmanagedPropertyList;
    }

    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getPackageVersion()
    {
        return $this->packageVersion;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string[]
     */
    public function getKeywordList()
    {
        return $this->keywordList;
    }

    /**
     * @return Author[]
     */
    public function getAuthorList()
    {
        return $this->authorList;
    }

    /**
     * @return Package[]
     */
    public function getProvidedPackageList()
    {
        return $this->providedPackageList;
    }

    /**
     * @return SuggestedPackage[]
     */
    public function getSuggestedPackageList()
    {
        return $this->suggestedPackageList;
    }

    /**
     * @return Support[]
     */
    public function getSupportList()
    {
        return $this->supportList;
    }

    /**
     * @return Autoload[]
     */
    public function getAutoloadList()
    {
        return $this->autoloadList;
    }

    /**
     * @return Autoload[]
     */
    public function getAutoloadDevList()
    {
        return $this->autoloadDevList;
    }

    /**
     * @return Package[]
     */
    public function getRequiredPackageList()
    {
        return $this->requiredPackageList;
    }

    /**
     * @return Package[]
     */
    public function getRequiredDevPackageList()
    {
        return $this->requiredDevPackageList;
    }

    /**
     * @return Script[]
     */
    public function getScriptList()
    {
        return $this->scriptList;
    }

    /**
     * @return array
     */
    public function getUnmanagedPropertyList()
    {
        return $this->unmanagedPropertyList;
    }
}

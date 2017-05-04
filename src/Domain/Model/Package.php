<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class Package implements ConfigurationItemInterface
{
    /** @var string */
    private $name;
    /** @var string */
    private $versionConstraint;
    /**
     * @param string $name
     * @param string $versionConstraint
     */
    public function __construct($name, $versionConstraint)
    {
        $this->name = $name;
        $this->versionConstraint = $versionConstraint;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersionConstraint()
    {
        return $this->versionConstraint;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getName();
    }
}

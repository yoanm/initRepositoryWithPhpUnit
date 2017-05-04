<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class Author implements ConfigurationItemInterface
{
    /** @var string */
    private $name;
    /** @var string */
    private $email;
    /** @var string */
    private $role;

    public function __construct($name, $email = null, $role = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getName();
    }
}

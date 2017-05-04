<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class Script implements ConfigurationItemInterface
{
    /** @var string */
    private $name;
    /** @var string */
    private $command;

    /**
     * @param string $name
     * @param string $command
     */
    public function __construct($name, $command)
    {
        $this->name = $name;
        $this->command = $command;
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
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getName();
    }
}

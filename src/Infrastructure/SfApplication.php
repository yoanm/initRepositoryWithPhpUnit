<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class SfApplication extends Application
{
    /**
     * @param Command[] $commandList
     */
    public function __construct(array $commandList = [])
    {
        parent::__construct();
        $this->addCommands($commandList);
    }
}

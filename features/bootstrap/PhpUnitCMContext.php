<?php
namespace Functional\Yoanm\PhpUnitConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Class PhpUnitCMContext
 */
class PhpUnitCMContext implements Context
{
    /** @var CommandRunnerContext */
    private $commandRunnerContext;

    /**
     * @param string            $commandArguments
     * @param PyStringNode|null $options
     */
    public function iExecutePhpUnitCMWith($commandName, $commandArguments, PyStringNode $options = null)
    {
        $this->commandRunnerContext->runCommand(
            $commandName,
            sprintf(
                '%s %s',
                $commandArguments,
                $options ? str_replace("\n", ' ', $options->getRaw()) : ''
            )
        );
    }

    public function iCleanPath($path)
    {
        @unlink(DefaultContext::getFilePath($path));
    }

    public function iCreateFakeOldFileAt($path)
    {
        file_put_contents(
            DefaultContext::getFilePath($path),
            file_get_contents(__DIR__.'/../fixtures/phpunit.full.xml')
        );
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function init(BeforeScenarioScope $scope)
    {
        $this->commandRunnerContext = $scope->getEnvironment()->getContext(CommandRunnerContext::class);
    }
}

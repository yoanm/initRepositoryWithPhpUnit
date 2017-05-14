<?php
namespace Functional\Yoanm\PhpUnitConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;

/**
 * Class ComposerCMContext
 */
class CreateContext extends PhpUnitCMContext
{
    const DEFAULT_NAME = 'name';

    /**
     * @Given /^I execute phpunitcm create with "(?<dest>[^"]+)" and following options:$/
     */
    public function iExecuteConsoleWithNameDestAndOption($dest = null, PyStringNode $options = null)
    {
        $commandArguments = sprintf('"%s"', DefaultContext::getBasePath($dest));
        $this->iCleanPath($dest);
        $this->iExecutePhpUnitCMWith('create', $commandArguments, $options);
    }

    /**
     * @Given /^I execute phpunitcm create with "(?<dest>[^"]+)"$/
     */
    public function iExecuteConsoleWithNameAndDest($dest)
    {
        $this->iExecuteConsoleWithNameDestAndOption($dest, null);
    }

    /**
     * @Given /^I execute phpunitcm create$/
     */
    public function iExecuteConsole()
    {
        $this->iExecuteConsoleWithNameDestAndOption(null, null);
    }

    /**
     * @Given /^I execute phpunitcm create with following options:$/
     */
    public function iExecuteConsoleWitOption(PyStringNode $options)
    {
        $this->iExecuteConsoleWithNameDestAndOption(null, $options);
    }
}

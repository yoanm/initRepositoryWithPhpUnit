<?php
namespace Functional\Yoanm\PhpUnitConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;

/**
 * Class ComposerCMContext
 */
class UpdateContext extends PhpUnitCMContext
{
    public function iExecuteConsoleWithDestAndOption($dest = null, PyStringNode $options = null)
    {
        $commandArguments = DefaultContext::getBasePath($dest);
        $this->iCreateFakeOldFileAt($dest);
        $this->iExecutePhpUnitCMWith('update', $commandArguments, $options);
    }

    /**
     * @Given /^I execute composercm update with "(?<dest>[^"]+)" and following options:$/
     */
    public function iExecuteConsoleWithNameAndOption($dest, PyStringNode $options)
    {
        $this->iExecuteConsoleWithDestAndOption($dest, $options);
    }

    /**
     * @Given /^I execute composercm update with following options:$/
     */
    public function iExecuteConsoleWitOption(PyStringNode $options)
    {
        $this->iExecuteConsoleWithDestAndOption(null, $options);
    }
}

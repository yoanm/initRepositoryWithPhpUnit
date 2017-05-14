<?php
namespace Functional\Yoanm\PhpUnitConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

/**
 * Class DefaultContext
 */
class DefaultContext implements Context
{
    const DEFAULT_DESTINATION = './build/behat';

    /** @var CommandRunnerContext */
    private $commandRunnerContext;

    public static function getFilePath($path = null)
    {
        return sprintf(
            '%s/%s',
            self::getBasePath($path),
            ConfigurationFile::FILENAME
        );
    }

    public static function getBasePath($path = null)
    {
        return trim(
            sprintf(
                '%s/%s',
                trim(self::DEFAULT_DESTINATION, '/'),
                trim($path, '/')
            ),
            '/'
        );
    }

    /**
     * @BeforeScenario
     */
    public function initDirectories()
    {
        $this->deleteDirectory(self::getBasePath());
        $this->iHaveAFolder('/');
    }

    /**
     * @Given /^I have no(?: configuration)? file(?: at "(?<filepath>[^"]+)")?$/
     */
    public function iHaveNoFileAt($filepath = null)
    {
        @unlink(self::getFilePath($filepath));
    }

    /**
     * @Given /^I have the folder "(?<path>[^"]+)"$/
     */
    public function iHaveAFolder($path)
    {
        @mkdir(self::getBasePath($path), 0777, true);
    }

    /**
     * @Then /^an exception must have been thrown$/
     */
    public function anExceptionMustHaveBeenThrown()
    {
        Assert::assertFalse(is_null($this->commandRunnerContext->getLastException()));
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should be:$/
     */
    public function configurationFileShouldBe($path = null, PyStringNode $inputs = null)
    {
        $this->configFileShouldBe($this->loadXml($inputs->getRaw()), $path);
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should contains:$/
     */
    public function configurationFileShouldContains($path = null, PyStringNode $inputs = null)
    {
        $this->configFileShouldContains($this->loadXml($inputs->getRaw()), $path);
    }

    /**
     * @Then /^I should have a configuration file at "(?<path>[^"]+)"$/
     */
    public function iShouldHaveAConfigurationAt($path)
    {
        if (!file_exists(self::getFilepath($path))) {
            throw new \Exception(
                sprintf(
                    "No configuration file was not created at %s",
                    $path
                )
            );
        }
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function init(BeforeScenarioScope $scope)
    {
        $this->commandRunnerContext = $scope->getEnvironment()->getContext(CommandRunnerContext::class);
    }

    protected function configFileShouldBe(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent(self::getFilepath($path));
        try {
            Assert::assertSame($expected, $currentConfiguration);
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            throw new \Exception(
                sprintf(
                    "Configuration file content not expected !\n Expected: %s\nActual: %s",
                    json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    json_encode($currentConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                )
            );
        }
    }

    /**
     * @param string $expected
     * @param string $path
     * @throws \Exception
     */
    protected function configFileShouldContains($expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent(self::getFilepath($path));
        try {
            Assert::assertContains($expected, $currentConfiguration);
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            throw new \Exception(
                sprintf(
                    "Configuration file content do not contains expected data !\n Expected: %s\nActual: %s",
                    $expected,
                    $currentConfiguration
                )
            );
        }
    }

    /**
     * @param $configFilePath
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getConfigurationFileContent($configFilePath)
    {
        return $this->loadXml(file_get_contents($configFilePath));
    }

    /**
     * @param string $encoded
     *
     * @return string
     */
    protected function loadXml($encoded)
    {
        return $encoded;
    }

    private function deleteDirectory($dir)
    {
        if ($handle = @opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ('.' != $file && '..' != $file) {
                    $path = implode(DIRECTORY_SEPARATOR, [$dir, $file]);
                    if (is_dir($path)) {
                        if (!@rmdir($path)) {
                            // Probably not empty => remove files inside
                            $this->deleteDirectory($path.DIRECTORY_SEPARATOR);
                        }
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($handle);
            if ('.' !== $dir) {
                rmdir($dir);
            }
        }
    }
}

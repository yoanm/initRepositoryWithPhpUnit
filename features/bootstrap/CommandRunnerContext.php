<?php
namespace Functional\Yoanm\PhpUnitConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Behat\EventDispatcher\Event\ExampleTested;
use Behat\Behat\EventDispatcher\Event\GherkinNodeTested;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Yoanm\BehatUtilsExtension\Context\BehatContextSubscriberInterface;
use Yoanm\PhpUnitConfigManager\Infrastructure\SfApplication;

/**
 * Class CommandRunnerContext
 */
class CommandRunnerContext implements Context, BehatContextSubscriberInterface
{
    /** @var SfApplication */
    private $application;
    /** @var ConsoleOutput */
    private $output;
    /** @var ArrayInput */
    private $intput;
    /** @var null|\Exception */
    private $lastException;
    /** @var CommandTester */
    private $tester;
    /** @var int */
    private $exitCode;
    /** @var null|string[] */
    private $templateFilePathList = null;

    /**
     * @Given /^I will use configuration template at "(?<filePath>[^"]+)" with:$/
     */
    public function iWillUseConfigurationTemplateAtWith($filePath, PyStringNode $content = null)
    {
        $filePath = DefaultContext::getBasePath($filePath);
        @mkdir(dirname($filePath));
        $this->templateFilePathList[] = $filePath;
        if ($content) {
            file_put_contents($filePath, $content->getRaw());
        }
    }

    /**
     * @Given /^I will use configuration template fixture "(?<name>[^"]+)"$/
     */
    public function iWillUseConfigurationTemplateFixture($name)
    {
        $this->templateFilePathList[] = sprintf('./features/fixtures/%s', trim($name));
    }

    /**
     * @return \Exception|null
     */
    public function getLastException()
    {
        return $this->lastException;
    }

    /**
     * @param string $commandName
     * @param string $commandArgs
     */
    public function runCommand($commandName, $commandArgs)
    {
        $command = $this->application->get($commandName);
        $input = new StringInput($commandArgs);
        $input->bind($command->getDefinition());
        $optionList = [];
        foreach ($input->getOptions() as $optionName => $optionValue) {
            $optionList['--' . $optionName] = $optionValue;
        }

        if (null != $this->templateFilePathList) {
            $optionList['--template'] = $this->templateFilePathList;
        }
        $this->templateFilePathList[] = null;

        $optionList = array_filter($optionList, function ($value) {
            if (is_array($value)) {
                return 0 < count($value);
            } else {
                return 0 < strlen($value);
            }
        });
        $inputs = array_merge($input->getArguments(), $optionList);
        $inputs['command'] = $command->getName();

        $this->tester = new CommandTester($command);
        try {
            $this->exitCode = $this->tester->execute($inputs);
            $this->lastException = null;
        } catch (\Exception $exception) {
            $this->lastException = $exception;
            $this->exitCode = $exception->getCode();
            var_dump('Exception : '.$exception->getMessage());
        }
    }

    /**
     * @return CommandTester
     */
    public function getCommandTester()
    {
        return $this->tester;
    }

    /**
     * @return SfApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param GherkinNodeTested $event
     */
    public function reset(GherkinNodeTested $event)
    {
        require_once(__DIR__ . '/../../vendor/autoload.php');

        $container = new ContainerBuilder();
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../src/Infrastructure/config'));

        $loader->load('application.xml');
        $loader->load('infra.xml');

        $this->application = $container->get('phpunit_config_manager.sf_app');
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScenarioTested::BEFORE => ['reset'],
            ExampleTested::BEFORE => ['reset'],
        ];
    }
}

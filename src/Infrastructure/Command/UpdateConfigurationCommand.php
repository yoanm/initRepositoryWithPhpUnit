<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList;
use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;

class UpdateConfigurationCommand extends AbstractTemplatableCommand
{
    const NAME = 'update';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'path';

    /** @var InputTransformer */
    private $inputTransformer;
    /** @var UpdateConfigurationFileList */
    private $updateConfiguration;

    public function __construct(
        InputTransformer $inputTransformer,
        UpdateConfigurationFileList $updateConfiguration,
        ConfigurationFileLoaderInterface $configurationLoader
    ) {
        parent::__construct($configurationLoader);

        $this->inputTransformer = $inputTransformer;
        $this->updateConfiguration = $updateConfiguration;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Will update a phpunit configuration file.')
// @codingStandardsIgnoreStart
            ->setHelp(<<<DESC
 - <info>keywords</info> will be appended to existing ones
 - <info>other plain values</info> (package name, version, ...) will replace old ones if they are already present, else they will be added
 - <info>nested values</info> (authors, autoload, script, ...) will replace old ones if they are already present, else they will be appended
DESC
            )
// @codingStandardsIgnoreEnd
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Existing onfiguration file path',
                '.'
            )
            ->addOption(
                InputTransformer::KEY_TYPE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package type. Ex : "library" / "project"'
            )
            ->addOption(
                InputTransformer::KEY_LICENSE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package license type'
            )
            ->addOption(
                InputTransformer::KEY_PACKAGE_VERSION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package version number. Ex : "X.Y.Z"'
            )
            ->addOption(
                InputTransformer::KEY_DESCRIPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package description'
            )
            ->addOption(
                InputTransformer::KEY_KEYWORD,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package keywords'
            )
            ->addOption(
                InputTransformer::KEY_AUTHOR,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package authors. Format "name[#email[#role]]'
            )
            ->addOption(
                InputTransformer::KEY_PROVIDED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages provided by this one. Ex : "package-name#version"'
            )
            ->addOption(
                InputTransformer::KEY_SUGGESTED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages suggested by this one. Ex : "package-name#description"'
            )
            ->addOption(
                InputTransformer::KEY_SUPPORT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package support urls. Ex : "type#url"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-0 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-4 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages PSR-0 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-4 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_REQUIRE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::KEY_REQUIRE_DEV,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required dev packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::KEY_SCRIPT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of scripts for the package. Ex : "script-name#command"'
            )
        ;
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument(self::ARGUMENT_CONFIGURATION_DEST_FOLDER);
        $configurationFileList = $this->loadTemplateConfigurationFileList($input);

        $configurationFileList[] = $this->getConfigurationFileLoader()->fromPath($path);

        if ($newConfigurationFile = $this->inputTransformer->fromCommandLine($input->getOptions())) {
            $configurationFileList[] = $newConfigurationFile;
        }
        $this->updateConfiguration->run(
            new UpdateConfigurationFileListRequest(
                $configurationFileList,
                $path
            )
        );
    }
}

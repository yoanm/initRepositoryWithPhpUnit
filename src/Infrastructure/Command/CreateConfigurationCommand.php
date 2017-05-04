<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList;
use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;

class CreateConfigurationCommand extends AbstractTemplatableCommand
{
    const NAME = 'create';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'destination';

    /** @var InputTransformer */
    private $inputTransformer;
    /** @var UpdateConfigurationFileList */
    private $updateConfigurationFile;

    /**
     * @param InputTransformer                 $inputTransformer
     * @param UpdateConfigurationFileList      $updateConfigurationFile
     * @param ConfigurationFileLoaderInterface $configurationLoader
     */
    public function __construct(
        InputTransformer $inputTransformer,
        UpdateConfigurationFileList $updateConfigurationFile,
        ConfigurationFileLoaderInterface $configurationLoader
    ) {
        parent::__construct($configurationLoader);

        $this->inputTransformer = $inputTransformer;
        $this->updateConfigurationFile = $updateConfigurationFile;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Will create a phpunit configuration file')
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Configuration file destination folder',
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
                'List of PSR-0 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-4 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-0 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-4 dev autoload. Ex : "namespace#path"'
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
        $configurationFileList = $this->loadTemplateConfigurationFileList($input);
        if ($newConfigurationFile = $this->loadConfigurationFile($input, $configurationFileList)) {
            $configurationFileList[] = $newConfigurationFile;
        }
        $this->updateConfigurationFile->run(
            new UpdateConfigurationFileListRequest(
                $configurationFileList,
                $input->getArgument(self::ARGUMENT_CONFIGURATION_DEST_FOLDER)
            )
        );
    }

    /**
     * @param InputInterface      $input
     * @param ConfigurationFile[] $configurationFileList
     *
     * @return null|ConfigurationFile
     */
    protected function loadConfigurationFile(InputInterface $input, array $configurationFileList)
    {
        $packageName = $input->getArgument(InputTransformer::KEY_PACKAGE_NAME);
        $inputList = $input->getOptions();
        if (null === $packageName) {
            $hasNameDefined = false;
            foreach ($configurationFileList as $configurationFile) {
                if ('' !== trim($configurationFile->getConfiguration()->getPackageName())) {
                    $hasNameDefined = true;
                    break;
                }
            }
            if (false === $hasNameDefined) {
                throw new InvalidArgumentException(
                    sprintf(
                        'A package name must be given if no template containing package name is given !',
                        gettype($packageName)
                    )
                );
            }
        } else {
            $inputList = [
                InputTransformer::KEY_PACKAGE_NAME => $packageName
            ] + $inputList;
        }

        if (0 === count($inputList)) {
            return null;
        }

        return $this->inputTransformer->fromCommandLine($inputList);
    }
}

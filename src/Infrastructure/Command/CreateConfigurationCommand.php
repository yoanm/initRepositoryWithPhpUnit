<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList;
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
            ->setDescription('Will create a phpunit configuration file.')
            ->setHelp('Separator for command line value is <info>##</info>.'."\n"
                .'Test suite items formats:'."\n"
                .'  - "test suite name<info>##</info>path"'."\n"
                .'  - "test suite name<info>##</info>path'
                    .'<info>##</info>attr_name1<info>##</info>attr_value1"'
                    .'<info>##</info>attr_name2<info>##</info>attr_value2<info>##</info>attr...."'."\n"
                .'Log items formats:'."\n"
                .'  - "type<info>##</info>target"'."\n"
                .'  - "type<info>##</info>target'
                    .'<info>##</info>attr_name1<info>##</info>attr_value1'
                    .'<info>##</info>attr_name2<info>##</info>attr_value2<info>##</info>attr...."'."\n"
                .'Php items formats:'."\n"
                .'  - "name<info>##</info>value"'."\n"
                .'  - "name<info>##</info>attr_name1<info>##</info>attr_value1"'."\n"
                .'  - "name<info>##</info>value'
                    .'<info>##</info>attr_name1<info>##</info>attr_value1'
                    .'<info>##</info>attr_name2<info>##</info>attr_value2<info>##</info>attr...."'."\n"
            )
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Configuration file destination folder',
                '.'
            )
            ->addOption(
                InputTransformer::KEY_CONFIG_ATTR,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Phpunit options. <info>Format </info><comment>"key##value"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_TEST_SUITE_FILE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Test suite file entry. <info>Format </info><comment>"test suite name##path[##attr_name##attr_value]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_TEST_SUITE_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Test suite directory entry. <info>Format </info><comment>"test suite name##path[##attr_name##attr_value]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_TEST_SUITE_EXCLUDED,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Test suite excluded entry. <info>Format </info><comment>"test suite name##path[##attr_name##attr_value]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_GROUP_INCLUDE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Included group name. <info>Format </info><comment>"group name"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_GROUP_EXCLUDE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Excluded group name. <info>Format </info><comment>"group name"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_FILTER_WHITELIST_FILE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'White list file entry. <info>Format </info><comment>"path"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_FILTER_WHITELIST_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'White list directory entry. <info>Format </info><comment>"path"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_FILE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'White list excluded file entry. <info>Format </info><comment>"path"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'White list excluded directory entry. <info>Format </info><comment>"path"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_LOG,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Logging item. <info>Format </info><comment>"type##target[##attr_name##attr_value]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_LISTENER,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Listener item. <info>Format </info><comment>"class[##file]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_LISTENER,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Listener item. <info>Format </info><comment>"class[##file]"</comment>'
            )
            ->addOption(
                InputTransformer::KEY_PHP,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Php item. <info>Format </info><comment>"name[##node_value][##attr_name##attr_value]"</comment>'
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
        if ($newConfigurationFile = $this->loadConfigurationFile($input)) {
            $configurationFileList[] = $newConfigurationFile;
        }
        if (0 === count($configurationFileList)) {
            throw new InvalidOptionException('At least one option should be used !');
        }
        $this->updateConfigurationFile->run(
            new UpdateConfigurationFileListRequest(
                $configurationFileList,
                $input->getArgument(self::ARGUMENT_CONFIGURATION_DEST_FOLDER)
            )
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return null|ConfigurationFile
     */
    protected function loadConfigurationFile(InputInterface $input)
    {
        return $this->inputTransformer->fromCommandLine($input->getOptions());
    }
}

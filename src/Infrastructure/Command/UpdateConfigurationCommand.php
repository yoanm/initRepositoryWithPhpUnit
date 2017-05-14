<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Application\Request\UpdateConfigurationFileListRequest;
use Yoanm\PhpUnitConfigManager\Application\UpdateConfigurationFileList;
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

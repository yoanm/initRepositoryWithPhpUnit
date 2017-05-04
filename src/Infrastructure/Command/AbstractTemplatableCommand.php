<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Yoanm\PhpUnitConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;

abstract class AbstractTemplatableCommand extends Command
{
    const OPTION_TEMPLATE = 'template';

    /** @var ConfigurationFileLoaderInterface */
    private $configurationFileLoader;

    /**
     * @param ConfigurationFileLoaderInterface $configurationFileLoader
     */
    public function __construct(ConfigurationFileLoaderInterface $configurationFileLoader)
    {
        parent::__construct();
        $this->configurationFileLoader = $configurationFileLoader;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption(
                self::OPTION_TEMPLATE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Path of the json template file. Will be used as default values.'
            )
        ;
    }

    /**
     * @return ConfigurationFileLoaderInterface
     */
    public function getConfigurationFileLoader()
    {
        return $this->configurationFileLoader;
    }

    /**
     * @param InputInterface $input
     *
     * @return ConfigurationFile[]
     */
    protected function loadTemplateConfigurationFileList(InputInterface $input)
    {
        $templatePathList = $input->getOption(self::OPTION_TEMPLATE);
        $templateConfigurationList = [];
        if (count($templatePathList)) {
            foreach ($templatePathList as $templatePath) {
                if (is_dir($templatePath)) {
                    $templateConfigurationList[] = $this->configurationFileLoader->fromPath($templatePath);
                } elseif (is_file($templatePath)) {
                    $templateConfigurationList[] = $this->configurationFileLoader->fromString(
                        file_get_contents($templatePath)
                    );
                } else {
                    throw new \UnexpectedValueException('Template path is nor a file or a path !');
                }
            }
        }

        return $templateConfigurationList;
    }
}

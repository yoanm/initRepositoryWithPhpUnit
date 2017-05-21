<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

class InputTransformer extends AbstractTransformer
{
    const SEPARATOR = '##';
    // Phpunit options
    const KEY_CONFIG_ATTR = 'config-attr';
    // Test suites
    const KEY_TEST_SUITE_FILE = 'test-suite-file';
    const KEY_TEST_SUITE_DIRECTORY = 'test-suite-directory';
    const KEY_TEST_SUITE_EXCLUDED = 'test-suite-excluded';
    // Groups
    const KEY_GROUP_INCLUDE = 'group-include';
    const KEY_GROUP_EXCLUDE = 'group-exclude';
    // Whitelist
    const KEY_FILTER_WHITELIST_FILE = 'filter-whitelist-file';
    const KEY_FILTER_WHITELIST_DIRECTORY = 'filter-whitelist-directory';
    const KEY_FILTER_EXCLUDED_WHITELIST_FILE = 'filter-whitelist-excluded-file';
    const KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY = 'filter-whitelist-excluded-directory';
    // Logging
    const KEY_LOG = 'log';
    // Listeners
    const KEY_LISTENER = 'listener';
    // Php options
    const KEY_PHP = 'php';

    const DEFAULT_VERSION = '1.0';
    const DEFAULT_ENCODING = 'UTF-8';

    /** @var TestSuitesInputItemTransformer */
    private $testSuitesInputItemTransformer;
    /** @var GroupsInputItemTransformer */
    private $groupsInputItemTransformer;
    /** @var FilterInputItemTransformer */
    private $filterInputItemTransformer;
    /** @var LoggingInputItemTransformer */
    private $loggingInputItemTransformer;
    /** @var ListenersInputItemTransformer */
    private $listenersInputItemTransformer;
    /** @var PhpInputItemTransformer */
    private $phpInputItemTransformer;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        TestSuitesInputItemTransformer $testSuitesInputItemTransformer,
        GroupsInputItemTransformer $groupsInputItemTransformer,
        FilterInputItemTransformer $filterInputItemTransformer,
        LoggingInputItemTransformer $loggingInputItemTransformer,
        ListenersInputItemTransformer $listenersInputItemTransformer,
        PhpInputItemTransformer $phpInputItemTransformer,
        SerializerInterface $serializer
    ) {
        $this->testSuitesInputItemTransformer = $testSuitesInputItemTransformer;
        $this->groupsInputItemTransformer = $groupsInputItemTransformer;
        $this->filterInputItemTransformer = $filterInputItemTransformer;
        $this->loggingInputItemTransformer = $loggingInputItemTransformer;
        $this->listenersInputItemTransformer = $listenersInputItemTransformer;
        $this->phpInputItemTransformer = $phpInputItemTransformer;
        $this->serializer = $serializer;
    }
    /**
     * @param $inputList
     *
     * @return ConfigurationFile|null
     */
    public function fromCommandLine($inputList, $prettify = false)
    {
        $configurationFile = $this->createConfigurationFile($inputList);
        return $configurationFile && true === $prettify
            ? $this->prettify($configurationFile)
            : $configurationFile;
    }

    /**
     * @param array $inputList
     *
     * @return ConfigurationFile|null
     */
    protected function createConfigurationFile(array $inputList)
    {
        $defaultKeyList = [
            self::KEY_CONFIG_ATTR,
            self::KEY_TEST_SUITE_FILE,
            self::KEY_TEST_SUITE_DIRECTORY,
            self::KEY_TEST_SUITE_EXCLUDED,
            self::KEY_GROUP_INCLUDE,
            self::KEY_GROUP_EXCLUDE,
            self::KEY_FILTER_WHITELIST_FILE,
            self::KEY_FILTER_WHITELIST_DIRECTORY,
            self::KEY_FILTER_EXCLUDED_WHITELIST_FILE,
            self::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY,
            self::KEY_LOG,
            self::KEY_LISTENER,
            self::KEY_PHP,
        ];
        $definedOptionList = array_intersect($defaultKeyList, array_keys($inputList));
        foreach ($definedOptionList as $key => $name) {
            if (0 === count($inputList[$name])) {
                unset($definedOptionList[$key]);
            }
        }
        if (0 === count($definedOptionList)) {
            return null;
        }


        return new ConfigurationFile(
            self::DEFAULT_VERSION,
            self::DEFAULT_ENCODING,
            [
                New Block(
                    new Configuration(
                        $this->extractPhpUnitOptions($inputList),
                        $this->extractConfigurationNode($inputList)
                    )
                ),
            ]
        );
    }

    /**
     * @param array $inputList
     *
     * @return ConfigurationItemInterface[]
     */
    protected function extractConfigurationNode(array $inputList)
    {
        return array_filter(
            [
                $this->testSuitesInputItemTransformer->extract($inputList),
                $this->groupsInputItemTransformer->extract($inputList),
                $this->filterInputItemTransformer->extract($inputList),
                $this->loggingInputItemTransformer->extract($inputList),
                $this->listenersInputItemTransformer->extract($inputList),
                $this->phpInputItemTransformer->extract($inputList),
            ],
            'is_object'
        );
    }

    /**
     * @param array $inputList
     *
     * @return Attribute[]
     */
    protected function extractPhpUnitOptions(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_CONFIG_ATTR]) && is_array($inputList[self::KEY_CONFIG_ATTR])) {
            foreach ($inputList[self::KEY_CONFIG_ATTR] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $attributeName = array_shift($data);
                $list[] = new Attribute($attributeName, array_shift($data));
            }
        }

        return $list;
    }



    /**
     * @param ConfigurationFile $configurationFile
     *
     * @return ConfigurationFile
     */
    private function prettify(ConfigurationFile $configurationFile)
    {
        // Following will add indentation and white space in order to format the configuration
        return $this->serializer->deserialize(
            $this->serializer->serialize(
                $configurationFile,
                PhpUnitEncoder::FORMAT,
                [
                    PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                    PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => false,
                    PhpUnitEncoder::LOAD_OPTIONS_CONTEXT_KEY => LIBXML_NOBLANKS,
                ]
            ),
            ConfigurationFile::class,
            PhpUnitEncoder::FORMAT,
            [
                PhpUnitEncoder::FORMAT_OUTPUT_CONTEXT_KEY => true,
                PhpUnitEncoder::PRESERVE_WHITESPACE_CONTEXT_KEY => false
            ]
        );
    }
}

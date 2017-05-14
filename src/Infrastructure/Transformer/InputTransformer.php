<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

class InputTransformer
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

    /** @var \DOMDocument */
    private $document;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
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
            self::SEPARATOR,
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
                new Configuration(
                    $this->extractConfigurationNode($inputList),
                    $this->extractPhpUnitOptions($inputList)
                ),
            ]
        );
    }

    /**
     * @param array $inputList
     *
     * @return array
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
     * @param array $inputList
     *
     * @return array
     */
    protected function extractConfigurationNode(array $inputList)
    {
        return array_filter(
            [
                $this->extractTestSuites($inputList),
                $this->extractGroups($inputList),
                $this->extractFilter($inputList),
                $this->extractLogging($inputList),
                $this->extractListeners($inputList),
                $this->extractPhp($inputList),
            ],
            'is_object'
        );
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractTestSuites(array $inputList)
    {
        $rawTestSuiteList = [];
        foreach ([self::KEY_TEST_SUITE_FILE, self::KEY_TEST_SUITE_DIRECTORY] as $inputKey) {
            if (isset($inputList[$inputKey]) && is_array($inputList[$inputKey])) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $data = $this->extractDataFromValue($inputValue);
                    $name = array_shift($data);
                    $rawTestSuiteList[$name][] = new TestSuiteItem(
                        self::KEY_TEST_SUITE_FILE === $inputKey
                            ? FilesystemItem::TYPE_FILE
                            : FilesystemItem::TYPE_DIRECTORY,
                        array_shift($data),
                        $this->convertToAttributeList($data)
                    );
                }
            }
        }
        if (isset($inputList[self::KEY_TEST_SUITE_EXCLUDED]) && is_array($inputList[self::KEY_TEST_SUITE_EXCLUDED])) {
            foreach ($inputList[self::KEY_TEST_SUITE_EXCLUDED] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $name = array_shift($data);
                $rawTestSuiteList[$name][] = new ExcludedTestSuiteItem(
                    array_shift($data),
                    $this->convertToAttributeList($data)
                );
            }
        }

        $testSuiteList = [];
        foreach ($rawTestSuiteList as $testSuiteName => $testSuiteItemList) {
            $testSuiteList[] = new TestSuite($testSuiteName, $this->appendNewLineNode($testSuiteItemList));
        }

        if (count($testSuiteList)) {
            return new TestSuites($this->appendNewLineNode($testSuiteList));
        }

        return null;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractGroups(array $inputList)
    {
        $rawGroupInclusionList = [];
        foreach ([self::KEY_GROUP_EXCLUDE, self::KEY_GROUP_INCLUDE] as $inputKey) {
            if (isset($inputList[$inputKey]) && is_array($inputList[$inputKey])) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $rawGroupInclusionList[$inputKey][] = new Group($inputValue);
                }
            }
        }

        $groupInclusionList = [];
        foreach ($rawGroupInclusionList as $inclusionType => $itemList) {
            $groupInclusionList[] = new GroupInclusion(
                $this->appendNewLineNode($itemList),
                self::KEY_GROUP_EXCLUDE === $inclusionType
            );
        }

        if (count($groupInclusionList)) {
            return new Groups($this->appendNewLineNode($groupInclusionList));
        }

        return null;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractFilter(array $inputList)
    {
        $whiteListItemList = [];
        $whiteListInputKeyList = [self::KEY_FILTER_WHITELIST_DIRECTORY, self::KEY_FILTER_WHITELIST_FILE];
        foreach ($whiteListInputKeyList as $inputKey) {
            if (isset($inputList[$inputKey]) && is_array($inputList[$inputKey])) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $data = $this->extractDataFromValue($inputValue);
                    $whiteListItemList[] = new WhiteListItem(
                        self::KEY_FILTER_WHITELIST_DIRECTORY == $inputKey
                            ? FilesystemItem::TYPE_DIRECTORY
                            : FilesystemItem::TYPE_FILE,
                        array_shift($data),
                        $this->convertToAttributeList($data)
                    );
                }
            }
        }
        $excludedWhiteListItemList = [];
        $excludedWhiteListInputKeyList = [
            self::KEY_FILTER_EXCLUDED_WHITELIST_FILE,
            self::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY
        ];
        foreach ($excludedWhiteListInputKeyList as $inputKey) {
            if (isset($inputList[$inputKey]) && is_array($inputList[$inputKey])) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $data = $this->extractDataFromValue($inputValue);
                    $excludedWhiteListItemList[] = new WhiteListItem(
                        self::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY == $inputKey
                            ? FilesystemItem::TYPE_DIRECTORY
                            : FilesystemItem::TYPE_FILE,
                        array_shift($data),
                        $this->convertToAttributeList($data)
                    );
                }
            }
        }

        if (count($excludedWhiteListItemList)) {
            $whiteListItemList[] = new ExcludedWhiteList($this->appendNewLineNode($excludedWhiteListItemList));
        }

        if (count($whiteListItemList)) {
            return new Filter([
                new WhiteList($this->appendNewLineNode($whiteListItemList))
            ]);
        }

        return null;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractLogging(array $inputList)
    {
        $logEntryList = [];
        if (isset($inputList[self::KEY_LOG]) && is_array($inputList[self::KEY_LOG])) {
            foreach ($inputList[self::KEY_LOG] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $type = array_shift($data);
                $target = array_shift($data);
                $attributeList = $this->convertToAttributeList($data);
                array_unshift(
                    $attributeList,
                    new Attribute('type', $type),
                    new Attribute('target', $target)
                );
                $logEntryList[] = new Log($attributeList);
            }
        }

        if (count($logEntryList)) {
            return new Logging($this->appendNewLineNode($logEntryList));
        }

        return null;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractListeners(array $inputList)
    {
        $listenerList = [];
        if (isset($inputList[self::KEY_LISTENER]) && is_array($inputList[self::KEY_LISTENER])) {
            foreach ($inputList[self::KEY_LISTENER] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $listenerList[] = new Listener(array_shift($data), array_shift($data));
            }
        }

        if (count($listenerList)) {
            return new Listeners($this->appendNewLineNode($listenerList));
        }

        return null;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractPhp(array $inputList)
    {
        $phpItemList = [];
        if (isset($inputList[self::KEY_PHP]) && is_array($inputList[self::KEY_PHP])) {
            foreach ($inputList[self::KEY_PHP] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $name = array_shift($data);
                $value = null;
                // Remaining values should come by two (key and value)
                // In case more values exists, the first one is the item value
                if (0 !== count($data)%2) {
                    $value = array_shift($data);
                }
                $attributeList = [];
                $isItemName = true;
                foreach ($data as $attributeRawValue) {
                    if (true === $isItemName) {
                        $attributeList[] = new Attribute('name', $attributeRawValue);
                        $isItemName = false;
                    } else {
                        $attributeList[] = new Attribute('value', $attributeRawValue);
                    }
                }
                $phpItemList[] = new PhpItem(
                    $name,
                    $value,
                    $attributeList
                );
            }
        }

        if (count($phpItemList)) {
            return new Php($this->appendNewLineNode($phpItemList));
        }

        return null;
    }

    /**
     * @param array $dataList
     * @return array
     */
    public function convertToAttributeList(array $dataList)
    {
        $attributeList = [];
        while ($key = array_shift($dataList)) {
            $attributeList[] = new Attribute($key, array_shift($dataList));
        }

        return $attributeList;
    }

    /**
     * @param string $value
     *
     * @return array
     */
    protected function extractDataFromValue($value)
    {
        return explode(self::SEPARATOR, $value);
    }

    /**
     * @param array $itemlist
     *
     * @return array
     */
    protected function appendNewLineNode(array $itemlist)
    {
        //$itemlist[] = new UnmanagedNode($this->getDocument()->createTextNode("\n"));

        return $itemlist;
    }

    /**
     * @return \DOMDocument
     */
    protected function getDocument()
    {
        if (null === $this->document) {
            $this->document = new \DOMDocument(self::DEFAULT_VERSION, self::DEFAULT_ENCODING);
        }

        return $this->document;
    }

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

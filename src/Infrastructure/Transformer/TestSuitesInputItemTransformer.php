<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;

class TestSuitesInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return TestSuites|null
     */
    public function extract(array $inputList)
    {
        $testSuiteList = $this->extractSuite($inputList);

        if (count($testSuiteList)) {
            return new TestSuites($testSuiteList);
        }

        return null;
    }

    /**
     * @param array $inputList
     * @return array
     */
    protected function extractSuite(array $inputList)
    {
        $rawTestSuiteList = $this->extractSuiteItemList($inputList);

        $rawTestSuiteList = $this->extractExcludedSuiteItem($inputList, $rawTestSuiteList);

        $testSuiteList = [];
        foreach ($rawTestSuiteList as $testSuiteName => $testSuiteItemList) {
            $testSuiteList[] = new TestSuite($testSuiteName, $testSuiteItemList);
        }
        return $testSuiteList;
    }

    /**
     * @param array $inputList
     * @return array
     */
    protected function extractSuiteItemList(array $inputList)
    {
        $rawTestSuiteList = [];
        foreach ([InputTransformer::KEY_TEST_SUITE_FILE, InputTransformer::KEY_TEST_SUITE_DIRECTORY] as $inputKey) {
            if ($this->inputValueListExistFor($inputList, $inputKey)) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $data = $this->extractDataFromValue($inputValue);
                    $name = array_shift($data);
                    $rawTestSuiteList[$name][] = new TestSuiteItem(
                        InputTransformer::KEY_TEST_SUITE_FILE === $inputKey
                           ? FilesystemItem::TYPE_FILE
                           : FilesystemItem::TYPE_DIRECTORY,
                        array_shift($data),
                        $this->convertToAttributeList($data)
                    );
                }
            }
        }

        return $rawTestSuiteList;
    }

    /**
     * @param array $inputList
     * @param array $rawTestSuiteList
     * @return array
     */
    protected function extractExcludedSuiteItem(array $inputList, array $rawTestSuiteList)
    {
        if ($this->inputValueListExistFor($inputList, InputTransformer::KEY_TEST_SUITE_EXCLUDED)) {
            foreach ($inputList[InputTransformer::KEY_TEST_SUITE_EXCLUDED] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $name = array_shift($data);
                $rawTestSuiteList[$name][] = new ExcludedTestSuiteItem(array_shift($data));
            }
        }

        return $rawTestSuiteList;
    }
}

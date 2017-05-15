<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;

class FilterInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return array
     */
    public function extract(array $inputList)
    {
        $whiteListItemList = $this->extractWhiteListItemList(
            $inputList,
            [
                InputTransformer::KEY_FILTER_WHITELIST_DIRECTORY,
                InputTransformer::KEY_FILTER_WHITELIST_FILE
            ],
            InputTransformer::KEY_FILTER_WHITELIST_DIRECTORY
        );
        $excludedWhiteListItemList = $this->extractWhiteListItemList(
            $inputList,
            [
                InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_FILE,
                InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY
            ],
            InputTransformer::KEY_FILTER_EXCLUDED_WHITELIST_DIRECTORY
        );

        if (count($excludedWhiteListItemList)) {
            $whiteListItemList[] = new ExcludedWhiteList($excludedWhiteListItemList);
        }

        if (count($whiteListItemList)) {
            return new Filter([
                new WhiteList($whiteListItemList)
            ]);
        }

        return null;
    }

    /**
     * @param array  $inputList
     * @param array  $whiteListInputKeyList
     * @param string $directoryInputKey
     * @return array
     */
    protected function extractWhiteListItemList(array $inputList, array $whiteListInputKeyList, $directoryInputKey)
    {
        $whiteListItemList = [];
        foreach ($whiteListInputKeyList as $inputKey) {
            if ($this->inputValueListExistFor($inputList, $inputKey)) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $data = $this->extractDataFromValue($inputValue);
                    $whiteListItemList[] = new WhiteListItem(
                        $directoryInputKey == $inputKey
                            ? FilesystemItem::TYPE_DIRECTORY
                            : FilesystemItem::TYPE_FILE,
                        array_shift($data),
                        $this->convertToAttributeList($data)
                    );
                }
            }
        }

        return $whiteListItemList;
    }
}

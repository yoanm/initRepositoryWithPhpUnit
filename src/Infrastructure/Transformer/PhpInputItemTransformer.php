<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;

class PhpInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return Php|null
     */
    public function extract(array $inputList)
    {
        $phpItemList = [];
        if (isset($inputList[InputTransformer::KEY_PHP]) && is_array($inputList[InputTransformer::KEY_PHP])) {
            foreach ($inputList[InputTransformer::KEY_PHP] as $inputValue) {
                list($name, $value, $attributeList) = $this->extractPhpItemDataFromValue($inputValue);
                $phpItemList[] = new PhpItem(
                    $name,
                    $value,
                    $attributeList
                );
            }
        }

        if (count($phpItemList)) {
            return new Php($phpItemList);
        }

        return null;
    }

    /**
     * @param $inputValue
     * @return array
     */
    protected function extractPhpItemDataFromValue($inputValue)
    {
        $data = $this->extractDataFromValue($inputValue);
        $name = array_shift($data);
        $value = null;
        // Remaining values should come by two (key and value)
        // In case more values exists, the first one is the item value
        if (0 !== count($data) % 2) {
            $value = array_shift($data);
        }

        $attributeList = $this->extractPhpItemAttributeList($data);

        return array($name, $value, $attributeList);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function extractPhpItemAttributeList(array $data)
    {
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
        return $attributeList;
    }
}

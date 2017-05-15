<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;

abstract class AbstractTransformer
{
    /**
     * @param array $dataList
     * @return array
     */
    protected function convertToAttributeList(array $dataList)
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
        return explode(InputTransformer::SEPARATOR, $value);
    }

    /**
     * @param array  $inputList
     * @param string $inputKey
     *
     * @return bool
     */
    protected function inputValueListExistFor(array $inputList, $inputKey)
    {
        return isset($inputList[$inputKey]) && is_array($inputList[$inputKey]);
    }
}

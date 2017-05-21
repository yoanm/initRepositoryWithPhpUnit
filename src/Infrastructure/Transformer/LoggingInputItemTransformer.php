<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Block;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;

class LoggingInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return Block|null
     */
    public function extract(array $inputList)
    {
        $logEntryList = $this->extractLogEntryList($inputList);

        if (count($logEntryList)) {
            return new Block(new Logging($logEntryList));
        }

        return null;
    }

    /**
     * @param array $inputList
     * @return array
     */
    protected function extractLogEntryList(array $inputList)
    {
        $logEntryList = [];
        if ($this->inputValueListExistFor($inputList, InputTransformer::KEY_LOG)) {
            foreach ($inputList[InputTransformer::KEY_LOG] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $type = array_shift($data);
                $target = array_shift($data);
                $attributeList = $this->convertToAttributeList($data);
                array_unshift(
                    $attributeList,
                    new Attribute('type', $type),
                    new Attribute('target', $target)
                );
                $logEntryList[] = new Block(new Log($attributeList));
            }
        }

        return $logEntryList;
    }
}

<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;

class ListenersInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return Listeners|null
     */
    public function extract(array $inputList)
    {
        $listenerList = [];
        if (isset($inputList[InputTransformer::KEY_LISTENER]) && is_array($inputList[InputTransformer::KEY_LISTENER])) {
            foreach ($inputList[InputTransformer::KEY_LISTENER] as $inputValue) {
                $data = $this->extractDataFromValue($inputValue);
                $listenerList[] = new Listener(array_shift($data), array_shift($data));
            }
        }

        if (count($listenerList)) {
            return new Listeners($listenerList);
        }

        return null;
    }
}

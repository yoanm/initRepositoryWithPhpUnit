<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;

class GroupsInputItemTransformer extends AbstractTransformer
{
    /**
     * @param array $inputList
     *
     * @return Groups|null
     */
    public function extract(array $inputList)
    {
        $rawGroupInclusionList = $this->extractGroupInclusionList($inputList);

        $groupInclusionList = [];
        foreach ($rawGroupInclusionList as $inclusionType => $itemList) {
            $groupInclusionList[] = new GroupInclusion(
                $itemList,
                InputTransformer::KEY_GROUP_EXCLUDE === $inclusionType
            );
        }

        if (count($groupInclusionList)) {
            return new Groups($groupInclusionList);
        }

        return null;
    }

    /**
     * @param array $inputList
     * @return array
     */
    protected function extractGroupInclusionList(array $inputList)
    {
        $rawGroupInclusionList = [];
        foreach ([InputTransformer::KEY_GROUP_INCLUDE, InputTransformer::KEY_GROUP_EXCLUDE] as $inputKey) {
            if ($this->inputValueListExistFor($inputList, $inputKey)) {
                foreach ($inputList[$inputKey] as $inputValue) {
                    $rawGroupInclusionList[$inputKey][] = new Group($inputValue);
                }
            }
        }
        return $rawGroupInclusionList;
    }
}

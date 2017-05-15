<?php
namespace Technical\Integration\Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\GroupsInputItemTransformer;
use Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\InputTransformer;

/**
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\GroupsInputItemTransformer
 * @covers Yoanm\PhpUnitConfigManager\Infrastructure\Transformer\AbstractTransformer
 */
class GroupsInputItemTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var GroupsInputItemTransformer */
    private $transformer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->transformer = new GroupsInputItemTransformer();
    }

    public function testExtractWithNothing()
    {
        $this->assertNull(
            $this->transformer->extract([])
        );
    }

    public function testExtract()
    {
        $group1 = 'group1';
        $group2 = 'group2';
        $group3 = 'group3';
        $group4 = 'group4';
        $inputList = [
            InputTransformer::KEY_GROUP_EXCLUDE => [$group1, $group2],
            InputTransformer::KEY_GROUP_INCLUDE => [$group3, $group4],
        ];

        $groups = $this->transformer->extract($inputList);

        $this->assertInstanceOf(Groups::class, $groups);
        $groupInclusionList = $groups->getItemList();
        $this->assertCount(2, $groupInclusionList);
        $this->assertContainsOnlyInstancesOf(GroupInclusion::class, $groupInclusionList);
        /** @var GroupInclusion $groupInclusion */
        $groupInclusion = array_shift($groupInclusionList);
        $this->assertFalse($groupInclusion->isExcluded());
        $itemList = $groupInclusion->getItemList();
        $this->assertCount(2, $itemList);
        $this->assertContainsOnlyInstancesOf(Group::class, $itemList);
        /** @var Group $group */
        $group = array_shift($itemList);
        $this->assertSame($group3, $group->getValue());
        $group = array_shift($itemList);
        $this->assertSame($group4, $group->getValue());

        $groupInclusion = array_shift($groupInclusionList);
        $this->assertTrue($groupInclusion->isExcluded());
        $itemList = $groupInclusion->getItemList();
        $this->assertCount(2, $itemList);
        $this->assertContainsOnlyInstancesOf(Group::class, $itemList);
        /** @var Group $group */
        $group = array_shift($itemList);
        $this->assertSame($group1, $group->getValue());
        $group = array_shift($itemList);
        $this->assertSame($group2, $group->getValue());
    }
}

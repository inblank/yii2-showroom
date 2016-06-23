<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Product;
use inblank\showroom\models\Group;
use yii\codeception\TestCase;

class GroupTest extends TestCase
{
    use Specify;

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
        ];
    }

    /**
     * Create group test
     */
    public function testGroupCreate()
    {
        $group4 = new Group();
        expect("we can't create group without name", $group4->save())->false();
        expect("we can see error `name`", $group4->getErrors())->hasKey('name');
        $group4->name = 'Group 4';
        expect('we can create group with name', $group4->save())->true();
        $group5 = new Group(['name' => 'Group 5']);
        expect('we can create other group', $group5->save())->true();
        $group6 = new Group(['name' => 'Group 4']);
        expect('we can create group with same name', $group6->save())->true();
        expect('and `slug` will be different', $group4->slug)->notEquals($group6->slug);
        $group7 = new Group(['name' => 'Group 7', 'slug' => $group4->slug]);
        expect("we can't create group with already exists slug", $group7->save())->false();
    }

    /**
     * Update group test
     */
    public function testGroupUpdate()
    {
        $group = Group::findOne(1);
        $oldSlug = $group->slug;
        $group->name = 'Changed group';
        expect('we can update group', $group->save())->true();
        expect('... and slug should be not changed', $group->slug)->equals($oldSlug);

        $group2 = Group::findOne(2);
        $group2->slug = $group->slug;
        expect("we can't update group with slug from other", $group2->save())->false();
        expect("we must see error `slug`", $group2->getErrors())->hasKey('slug');
    }

    /**
     * Update group test
     */
    public function testGroupDelete()
    {
        $group = Group::findOne(3);
        expect('we can delete group without products', $group->delete())->internalType('integer');
        $group1 = Group::findOne(1);
        expect("we can't delete group with products", $group1->delete())->false();
        $group1->getDb()->createCommand()->update(Product::tableName(), ['group_id' => null], ['group_id' => 1])->execute();
        expect("after change products groups we can delete group", $group1->delete())->internalType('integer');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Product;
use inblank\showroom\models\Type;
use yii;
use yii\codeception\TestCase;

class TypeTest extends TestCase
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
     * Create type test
     */
    public function testTypeCreate()
    {
        $type4 = new Type();
        expect("we can't create type without name", $type4->save())->false();
        expect("we can see error `name`", $type4->getErrors())->hasKey('name');
        $type4->name = 'Type 4';
        expect('we can create type with name', $type4->save())->true();
        $type5 = new Type(['name' => 'Type 5']);
        expect('we can create other type', $type5->save())->true();
        $type6 = new Type(['name' => 'Type 4']);
        expect('we can create type with same name', $type6->save())->true();
        expect('and `slug` will be different', $type4->slug)->notEquals($type6->slug);
        $type7 = new Type(['name' => 'Type 7', 'slug' => $type4->slug]);
        expect("we can't create type with already exists slug", $type7->save())->false();
    }

    /**
     * Update type test
     */
    public function testTypeUpdate()
    {
        $type = Type::findOne(1);
        $oldSlug = $type->slug;
        $type->name = 'Changed type';
        expect('we can update type', $type->save())->true();
        expect('... and slug should be not changed', $type->slug)->equals($oldSlug);

        $type2 = Type::findOne(2);
        $type2->slug = $type->slug;
        expect("we can't update type with slug from other", $type2->save())->false();
        expect("we must see error `slug`", $type2->getErrors())->hasKey('slug');
    }

    /**
     * Update type test
     */
    public function testTypeDelete()
    {
        $type = Type::findOne(3);
        expect('we can delete type without products', $type->delete())->internalType('integer');
        $type1 = Type::findOne(1);
        expect("we can't delete type with products", $type1->delete())->false();
        $type1->getDb()->createCommand()->update(Product::tableName(), ['type_id' => null], ['type_id' => 1])->execute();
        expect("after change products types we can delete type", $type1->delete())->internalType('integer');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

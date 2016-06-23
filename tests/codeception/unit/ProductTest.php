<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Price;
use inblank\showroom\models\Product;
use inblank\showroom\models\Group;
use yii\codeception\TestCase;

class ProductTest extends TestCase
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
     * Create product test
     */
    public function testProductCreate()
    {
        $product5 = new Product();
        expect("we can't create product without name", $product5->save())->false();
        expect("we can see error `name`", $product5->getErrors())->hasKey('name');
        $product5->name = 'Test product 5';
        expect('we can create group with name and empty group', $product5->save())->true();
        expect('product create date must be set', $product5->created_at)->notEmpty();
        $product6 = new Product(['name' => 'Test product 6', 'group_id'=>1]);
        expect('we can create product with exists group', $product6->save())->true();
        $product7 = new Product(['name' => 'Test product 5', 'group_id'=>4]);
        expect("we can't create product with not existing group", $product7->save())->false();
        expect("we must see error `group_id`", $product7->getErrors())->hasKey('group_id');
        $product7->group_id=2;
        expect('we can create product with same name', $product7->save())->true();
        expect('and `slug` will be different', $product5->slug)->notEquals($product7->slug);
        $product8 = new Product(['name' => 'Test product 7', 'slug'=>$product6->slug]);
        expect("we can't create product with already exists slug", $product8->save())->false();
        // TODO create with the sellers existing or not
    }

    /**
     * Update product test
     */
    public function testProductUpdate()
    {
        $product = Product::findOne(1);
        $oldSlug = $product->slug;
        $product->name = 'Changed product';
        expect('we can update product', $product->save())->true();
        expect('... and slug should be not changed', $product->slug)->equals($oldSlug);
        $product->group_id=2;
        expect("we can change the product group to an existing", $product->save())->true();
        $product->group_id=4;
        expect("we can't change the product group to an not existing", $product->save())->false();
        expect("we must see error `group_id`", $product->getErrors())->hasKey('group_id');

        $product2 = Product::findOne(2);
        $product2->slug = $product->slug;
        expect("we can't update product with slug from other", $product2->save())->false();
        expect("we must see error `slug`", $product2->getErrors())->hasKey('slug');
        // TODO test update seller
    }

    /**
     * Update product test
     */
    public function testProductDelete()
    {
        $product = Product::findOne(3);
        expect('we can delete product with seller', $product->delete())->internalType('integer');
        $product = Product::findOne(1);
        expect("we can't delete product without seller if there are prices", $product->delete())->false();
        Price::deleteAll(['product_id'=>1]);
        expect("after delete prices we can delete product", $product->delete())->internalType('integer');
    }

    /**
     * Product relations test
     */
    public function testProductRelations()
    {
        $product = Product::findOne(1);
        $group = Group::findOne($product->group_id);
        expect('product must have group', $group)->notNull();
        expect('group must have id=2', $group->id)->equals(2);
        expect("groups can be equals", $product->group->id)->equals($group->id);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

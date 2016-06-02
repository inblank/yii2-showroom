<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Price;
use inblank\showroom\models\Product;
use inblank\showroom\models\Type;
use yii;
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
        expect('we can create type with name and empty type', $product5->save())->true();
        expect('product create date must be set', $product5->created_at)->notEmpty();
        $product6 = new Product(['name' => 'Test product 6', 'type_id'=>1]);
        expect('we can create product with exists type', $product6->save())->true();
        $product7 = new Product(['name' => 'Test product 5', 'type_id'=>4]);
        expect("we can't create product with not existing type", $product7->save())->false();
        expect("we must see error `type_id`", $product7->getErrors())->hasKey('type_id');
        $product7->type_id=2;
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
        $product->type_id=2;
        expect("we can change the product type to an existing", $product->save())->true();
        $product->type_id=4;
        expect("we can't change the product type to an not existing", $product->save())->false();
        expect("we must see error `type_id`", $product->getErrors())->hasKey('type_id');

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
        $type = Type::findOne($product->type_id);
        expect('product must have type', $type)->notNull();
        expect('type must have id=2', $type->id)->equals(2);
        expect("types can be equals", $product->type->id)->equals($type->id);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

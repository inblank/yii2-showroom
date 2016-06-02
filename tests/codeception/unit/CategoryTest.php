<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\CategoriesProducts;
use inblank\showroom\models\Product;
use inblank\showroom\models\Category;
use yii;
use yii\codeception\TestCase;

class CategoryTest extends TestCase
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
     * Create category test
     */
    public function testCategoryCreate()
    {
        $category4 = new Category();
        expect("we can't create category without name", $category4->save())->false();
        expect("we can see error `name`", $category4->getErrors())->hasKey('name');
        $category4->name = 'Category 4';
        expect('we can create category with name', $category4->save())->true();
        $category5 = new Category(['name' => 'Category 5']);
        expect('we can create other category', $category5->save())->true();
        $category6 = new Category(['name' => 'Category 4']);
        expect('we can create category with same name', $category6->save())->true();
        expect('and `slug` will be different', $category4->slug)->notEquals($category6->slug);
        $category7 = new Category(['name' => 'Category 7', 'slug' => $category4->slug]);
        expect("we can't create category with already exists slug", $category7->save())->false();
    }

    /**
     * Update category test
     */
    public function testCategoryUpdate()
    {
        $category = Category::findOne(1);
        $oldSlug = $category->slug;
        $category->name = 'Changed category';
        expect('we can update category', $category->save())->true();
        expect('... and slug should be not changed', $category->slug)->equals($oldSlug);

        $category2 = Category::findOne(2);
        $category2->slug = $category->slug;
        expect("we can't update category with slug from other", $category2->save())->false();
        expect("we must see error `slug`", $category2->getErrors())->hasKey('slug');
    }

    /**
     * Update category test
     */
    public function testCategoryDelete()
    {
        $category = Category::findOne(3);
        expect('we can delete category without products', $category->delete())->internalType('integer');
        $category1 = Category::findOne(1);
        expect("we can't delete category with products", $category1->delete())->false();
        $category1->getDb()->createCommand()->delete(CategoriesProducts::tableName(), ['category_id' => 1])->execute();
        expect("after delete products from category we can delete category", $category1->delete())->internalType('integer');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

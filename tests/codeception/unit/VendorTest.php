<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Price;
use inblank\showroom\models\Product;
use inblank\showroom\models\Vendor;
use yii;
use yii\codeception\TestCase;

class VendorTest extends TestCase
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
     * Create vendor test
     */
    public function testVendorCreate()
    {
        $vendor3 = new Vendor();
        expect("we can't create vendor without name", $vendor3->save())->false();
        expect("we can see error `name`", $vendor3->getErrors())->hasKey('name');
        $vendor3->name = 'Vendor 3';
        expect('we can create vendor with name', $vendor3->save())->true();
        $vendor4 = new Vendor(['name' => 'Vendor 4']);
        expect('we can create other vendor', $vendor4->save())->true();
        $vendor5 = new Vendor(['name' => 'Vendor 3']);
        expect('we can create vendor with same name', $vendor5->save())->true();
        expect('and `slug` will be different', $vendor3->slug)->notEquals($vendor5->slug);
        $vendor6 = new Vendor(['name' => 'Vendor 5', 'slug' => $vendor4->slug]);
        expect("we can't create vendor with already exists slug", $vendor6->save())->false();
    }

    /**
     * Update vendor test
     */
    public function testVendorUpdate()
    {
        $vendor = Vendor::findOne(1);
        $oldSlug = $vendor->slug;
        $vendor->name = 'Changed vendor';
        expect('we can update vendor', $vendor->save())->true();
        expect('... and slug should be not changed', $vendor->slug)->equals($oldSlug);

        $vendor2 = Vendor::findOne(2);
        $vendor2->slug = $vendor->slug;
        expect("we can't update vendor with slug from other", $vendor2->save())->false();
        expect("we must see error `slug`", $vendor2->getErrors())->hasKey('slug');
    }

    /**
     * Update vendor test
     */
    public function testVendorDelete()
    {
        $vendor = Vendor::findOne(2);
        expect('we can delete vendor without products', $vendor->delete())->internalType('integer');
        $vendor1 = Vendor::findOne(1);
        expect("we can't delete vendor with prices", $vendor1->delete())->false();
        $vendor1->getDb()->createCommand()->delete(Price::tableName(), ['vendor_id' => 1])->execute();
        expect("after delete prices vendors we can delete vendor", $vendor1->delete())->internalType('integer');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

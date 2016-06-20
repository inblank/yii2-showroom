<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Seller;
use tests\codeception\_fixtures\UserFixture;
use yii;
use yii\codeception\TestCase;

class SellerTest extends TestCase
{
    use Specify;

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }

    /**
     * Create vendor test
     */
    public function testSellerCreate()
    {
        $this->specify("we want to create the seller", function(){
            $seller = new Seller([
                'user_id' => 999,
            ]);
            expect("we can't create the seller without name and not exists user", $seller->save())->false();
            $errors = $seller->getErrors();
            expect("we must see `name` error", $errors)->hasKey('name');
            expect("we must see `user_id` error", $errors)->hasKey('user_id');
            $seller->name = 'Test seller';
            $seller->user_id = 1;
            expect("we can create the seller with name and exist user", $seller->save())->true();

            $seller1 = new Seller([
                'name' => 'Test seller 2'
            ]);
            expect("we can create the seller without user", $seller1->save())->true();

            $seller2 = new Seller([
                'name' => $seller->name,
                'slug' => $seller->slug,
            ]);
            expect("we can't create the seller with same slug", $seller2->save())->false();
            $seller2->slug = '';
            expect("we can create the seller with same name", $seller2->save())->true();
            expect("we can see different slug", $seller2->slug)->notEquals($seller->name);
        });
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}

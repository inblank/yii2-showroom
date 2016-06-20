<?php

namespace tests\codeception;

use Codeception\Specify;
use inblank\showroom\models\Seller;
use tests\codeception\_fixtures\SellerFixture;
use tests\codeception\_fixtures\UserFixture;
use yii;
use yii\codeception\TestCase;

class SellerTest extends TestCase
{
    use Specify;

    public $defaultLogoSource = 'logo.svg';
    public $logoSource = 'test-512x512.png';


    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
            'seller' => SellerFixture::className(),
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

    protected function logo($name)
    {
        return __DIR__ . '/../_data/' . $name;
    }

    public function testSellerLogo()
    {
        $this->specify("we want to change seller logo", function () {
            $defaultLogo = Seller::getLogoPath() . '/' . Seller::getModule()->defaultLogo;

            /** @var Seller $seller */
            $seller = $this->getFixture('seller')->getModel('seller_empty_logo');
            $oldLogo = $seller->logo;

            $logo = $this->logo($this->logoSource);
            expect("we can change logo on existing file", $seller->changeLogo($logo))->true();
            expect('logo must be changed', $seller->logo)->notEquals($oldLogo);
            expect('we can see new logo image', file_exists(Seller::getLogoPath() . '/' . $seller->logo))->true();
            expect("default logo cannot be removed", file_exists($defaultLogo))->true();
            $newLogo = $seller->logo;

            expect("we can change logo", $seller->changeLogo($logo))->true();
            expect("old logo must be removed", file_exists(Seller::getLogoPath() . '/' . $newLogo))->false();
            expect('logo must be changed', $seller->logo)->notEquals($newLogo);
            expect('we can see new logo image', file_exists(Seller::getLogoPath() . '/' . $seller->logo))->true();
            expect("default logo cannot be removed", file_exists($defaultLogo))->true();
            $newLogo = $seller->logo;

            expect("we can't change logo on not existing file", $seller->changeLogo('123'))->false();
            expect('we can see same logo name', $seller->logo)->equals($newLogo);
            expect('we can see new logo image', file_exists(Seller::getLogoPath() . '/' . $seller->logo))->true();
            expect("default logo must be", file_exists($defaultLogo))->true();

            $seller->resetLogo();
            expect('we can see default logo name', $seller->logo)->equals($this->defaultLogoSource);
            expect('we cannot see logo image', file_exists(Seller::getLogoPath() . '/' . $newLogo))->false();
            expect("default logo must be", file_exists($defaultLogo))->true();
        });

    }

    protected function setUp()
    {
        parent::setUp();
        Yii::setAlias('@webroot', realpath(__DIR__ . '/../app/web'));
        // copy default logo
        copy($this->logo($this->defaultLogoSource), Seller::getLogoPath() . '/' . Seller::getModule()->defaultLogo);
    }

    protected function tearDown()
    {
        parent::tearDown();
        yii\helpers\FileHelper::removeDirectory(Seller::getLogoPath());
    }
}

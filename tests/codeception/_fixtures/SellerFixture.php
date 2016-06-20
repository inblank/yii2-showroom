<?php
namespace tests\codeception\_fixtures;

use yii\test\ActiveFixture;

class SellerFixture extends ActiveFixture
{
    public $modelClass = 'inblank\showroom\models\Seller';
    public $dataFile = '@tests/codeception/_fixtures/data/seller.php';
}

<?php
namespace tests\codeception\_fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $dataFile = '@tests/codeception/_fixtures/data/user.php';
}

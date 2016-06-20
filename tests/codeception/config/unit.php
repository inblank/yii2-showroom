<?php
return [
    'id' => 'unitTest',
    'basePath' => __DIR__ . '/../app',
    'components'=>[
        'db'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=testdb',
            'username' => 'travis',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'bootstrap'=>[
        [
            'class'=>'inblank\showroom\Bootstrap',
        ],
    ],
    'modules'=>[
        'showroom'=>[
            'class'=>'inblank\showroom\Module',
            'modelMap' => [
                'User' => 'app\models\User',
            ],
        ],
    ],
];

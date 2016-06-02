<?php

namespace tests\codeception\_pages;

use Yii;
use yii\codeception\BasePage;

/**
 * 
 *
 * @property \FunctionalTester $actor
 */
class MainPage extends BasePage
{
    /** @inheritdoc */
    public $route = '/site/index';
}

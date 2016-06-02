<?php
/**
 * @var $scenario
 */
use tests\codeception\_pages\MainPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that front page work');

$page = MainPage::openBy($I);
$I->see('Frontpage');

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\SellerAddress */
/* @var $seller \inblank\showroom\models\Seller */

$this->title = Yii::t('showroom_backend', 'Create Address');
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Sellers'), 'url' => ['/showroom/seller/index']];
$this->params['breadcrumbs'][] = ['label' => $seller->name, 'url' => ['/showroom/seller/view', 'id'=>$seller->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Addresses'), 'url' => ['list', 'seller_id'=>$seller->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-address-create">

    <h1><?= Html::encode($this->title. ': ' . $seller->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

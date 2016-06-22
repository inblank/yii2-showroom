<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\SellerAddress */
/* @var $seller \inblank\showroom\models\Seller */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Sellers'), 'url' => ['/showroom/seller/index']];
$this->params['breadcrumbs'][] = ['label' => $seller->name, 'url' => ['/showroom/seller/view', 'id' => $seller->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Addresses'), 'url' => ['list', 'seller_id' => $seller->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-address-view">

    <h1><?= Html::encode(Yii::t('showroom_general', 'Addresses') . ' : ' . $seller->name . ' : ' . $this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('showroom_backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('showroom_backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('showroom_backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'seller_id',
            'sort',
            'title',
            'lat',
            'lng',
            'address:ntext',
            'emails:ntext',
            'phones:ntext',
            'persons:ntext',
            'schedule:ntext',
            'description:ntext',
        ],
    ]) ?>

</div>

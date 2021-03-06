<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Seller */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Sellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('showroom_backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('showroom_general', 'Addresses') . ' ('.$model->getAddresses()->count().')', ['/showroom/seller-address/list', 'seller_id' => $model->id], ['class'=>'btn btn-info'] )?>
        <?= Html::a(Yii::t('showroom_backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('showroom_backend', 'Are you sure you want to delete this seller?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="logo">
        <?= Html::img($model->imageUrl, ['width'=>150, 'height'=>150])?>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label'=>Yii::t('showroom_backend', 'User'),
                'attribute'=>'user.name',
            ],
            'name',
            'slug',
            'profile.web',
            'profile.description:html',
            'created_at',
        ],
    ]) ?>

</div>

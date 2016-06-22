<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel inblank\showroom\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('showroom_general', 'Sellers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('showroom_backend', 'Create Seller'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'id',
                'headerOptions'=>[
                    'width'=>'1%'
                ],
            ],
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => function($model){
                    return Html::img($model->imageUrl, ['width'=>40, 'height'=>40]);
                }
            ],
            'name',
            'slug',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel inblank\showroom\models\VendorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('showroom_general', 'Vendors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(
            Yii::t(
                'showroom_general',
                'New {name}',
                ['name' => Yii::t('showroom_general', 'Vendor')]
            ),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slug',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

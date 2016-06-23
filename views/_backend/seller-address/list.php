<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel inblank\showroom\models\SellerAddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seller inblank\showroom\models\Seller */

$this->title = Yii::t('showroom_general', 'Addresses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Sellers'), 'url' => ['/showroom/seller/index']];
$this->params['breadcrumbs'][] = ['label' => $seller->name, 'url' => ['/showroom/seller/view', 'id'=>$seller->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-address-index">

    <h1><?= Html::encode($this->title . ': ' . $seller->name ) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('showroom_backend', 'Create Address'), ['create', 'seller_id'=>$seller->id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            //'lat',
            // 'lng',
            'address:ntext',
            // 'emails:ntext',
            // 'phones:ntext',
            // 'persons:ntext',
            // 'schedule:ntext',
            // 'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

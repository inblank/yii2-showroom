<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Vendor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('showroom_general', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('showroom_general', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('showroom_backend', 'Are you sure you want to delete this {name}?', ['name'=>Yii::t('showroom_general', 'Vendor')]),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'slug',
            'name',
        ],
    ]) ?>

</div>

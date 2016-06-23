<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Group */

$this->title = Yii::t('showroom_general', 'Update {modelClass}: ', [
    'modelClass' => 'Group',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('showroom_general', 'Update');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

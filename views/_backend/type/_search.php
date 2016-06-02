<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\TypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('showroom_general', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('showroom_general', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

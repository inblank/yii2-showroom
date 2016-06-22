<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Seller */
/* @var $form yii\widgets\ActiveForm */
/* @var $usersList array */
?>

<div class="seller-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'logo')->widget(\kartik\file\FileInput::className(), [
            'options' => [
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'defaultPreviewContent' => '<img src="'.$model->imageDefaultUrl.'" width="100" height="100" alt="Seller logo">',
                'initialPreview' => $model->hasImage() ? [
                    Html::img($model->imageUrl, ['class'=>'file-preview-image']),
                ] : [],
                'showUpload' => false
            ]
        ]
    ) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->dropDownList($usersList, ['prompt'=>Yii::t('showroom_backend', 'Select user')]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('showroom_backend', 'Create') : Yii::t('showroom_frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

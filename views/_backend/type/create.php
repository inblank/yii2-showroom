<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Type */

$this->title = Yii::t('showroom_general', 'Create Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

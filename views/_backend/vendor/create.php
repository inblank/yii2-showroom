<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Vendor */

$this->title = Yii::t('showroom_general', 'New {name}', ['name'=>Yii::t('showroom_general', 'Vendor')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

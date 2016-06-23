<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model inblank\showroom\models\Group */

$this->title = Yii::t('showroom_backend', 'Create Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('showroom_general', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

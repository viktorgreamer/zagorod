<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Estimate */

$this->title = 'Update Estimate: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->estimate_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="estimate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

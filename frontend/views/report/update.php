<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = 'Update Report: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->report_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="report-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

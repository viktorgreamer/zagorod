<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TableCells */

$this->title = 'Update Table Cells: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Table Cells', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="table-cells-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

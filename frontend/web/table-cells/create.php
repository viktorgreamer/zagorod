<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TableCells */

$this->title = 'Create Table Cells';
$this->params['breadcrumbs'][] = ['label' => 'Table Cells', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-cells-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TableHistory */

$this->title = 'Create Table History';
$this->params['breadcrumbs'][] = ['label' => 'Table Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

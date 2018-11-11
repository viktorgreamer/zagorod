<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */

$this->title = 'Update Base Station: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Base Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="base-station-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

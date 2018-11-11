<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStationGroup */

$this->title = 'Update Base Station Group: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Base Station Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="base-station-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

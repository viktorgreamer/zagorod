<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BaseStationGroup */

$this->title = 'Create Base Station Group';
$this->params['breadcrumbs'][] = ['label' => 'Base Station Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

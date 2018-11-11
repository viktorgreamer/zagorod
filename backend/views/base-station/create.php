<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */

$this->title = 'Create Base Station';
$this->params['breadcrumbs'][] = ['label' => 'Base Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

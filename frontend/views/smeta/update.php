<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */

$this->title = 'Update Smeta: ' . $model->smeta_id;
$this->params['breadcrumbs'][] = ['label' => 'Smetas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->smeta_id, 'url' => ['view', 'id' => $model->smeta_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="smeta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

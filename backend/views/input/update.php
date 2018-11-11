<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Input */

$this->title = 'Редактирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Inputs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->input_id, 'url' => ['view', 'id' => $model->input_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="input-update">

    <?php if (!Yii::$app->request->isAjax) { ?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Output */

$this->title = 'Редактирование исходящих данных: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Исходящие данные', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->output_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="output-update">


    <?php if (!Yii::$app->request->isAjax) { ?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

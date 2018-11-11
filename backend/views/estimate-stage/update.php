<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EstimateStage */

$this->title = 'Редатировать этап: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Этапы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->stage_id, 'url' => ['view', 'id' => $model->stage_id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="estimate-stage-update">

    <?php if (!Yii::$app->request->isAjax) { ?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

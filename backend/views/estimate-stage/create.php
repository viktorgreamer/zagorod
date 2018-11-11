<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\EstimateStage */

$this->title = 'Добавить этап';
$this->params['breadcrumbs'][] = ['label' => 'Estimate Stages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-stage-create">

 <?php if (!Yii::$app->request->isAjax) { ?>

   <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

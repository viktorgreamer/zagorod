<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EstimateStage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estimate-stage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'estimate_id')->textInput()->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'event_id')->dropDownList([0 => 'нет']+ $model->mapEvents()) ?>
    <?= $form->field($model, 'inInput')->dropDownList($model->mapStatuses()) ?>
    <?= $form->field($model, 'inOutput')->dropDownList($model->mapStatuses()) ?>


  <!--  --><?/*= $form->field($model, 'priority')->textInput() */?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

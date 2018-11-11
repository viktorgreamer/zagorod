<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OutputSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="output-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'output_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'formula') ?>

    <?= $form->field($model, 'estimate_id') ?>

    <?= $form->field($model, 'stage_id') ?>


    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

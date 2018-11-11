<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InputSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="input-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'input_id') ?>

    <?= $form->field($model, 'estimate_id') ?>

    <?= $form->field($model, 'stage_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'validation_rule_id') ?>

    <?php // echo $form->field($model, 'multiple') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

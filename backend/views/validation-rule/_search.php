<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ValidationRuleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="validation-rule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'max') ?>

    <?= $form->field($model, 'min') ?>

    <?php // echo $form->field($model, 'preg_match') ?>

    <?php // echo $form->field($model, 'required') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

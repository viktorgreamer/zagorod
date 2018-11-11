<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ValidationRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="validation-rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList($model->mapTypes()) ?>

    <?= $form->field($model, 'max')->textInput() ?>

    <?= $form->field($model, 'min')->textInput() ?>

    <?= $form->field($model, 'preg_match')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mask')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'required')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

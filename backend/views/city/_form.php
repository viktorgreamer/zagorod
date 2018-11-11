<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_id')->dropDownList($model->mapRegions()) ?>

    <?= $form->field($model, 'sand_shipment')->textInput() ?>

    <?= $form->field($model, 'sand_cost')->textInput() ?>

    <?= $form->field($model, 'rubble_shipment')->textInput() ?>

    <?= $form->field($model, 'rubble_cost')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

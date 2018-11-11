<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'articul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complex_of_works')->textInput() ?>

    <?= $form->field($model, 'measure')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'check')->textInput() ?>

    <?= $form->field($model, 'product_type')->textInput() ?>

    <?= $form->field($model, 'material_type')->textInput() ?>

    <?= $form->field($model, 'sg_sht')->textInput() ?>

    <?= $form->field($model, 'manufacturer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'articul_man')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_cost')->textInput() ?>

    <?= $form->field($model, 'self_cost')->textInput() ?>

    <?= $form->field($model, 'link_to_numenclature')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'check1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'r')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_station_bux')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'station_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TableCells */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="table-cells-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tr_id')->textInput() ?>

    <?= $form->field($model, 'td_id')->textInput() ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'table_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

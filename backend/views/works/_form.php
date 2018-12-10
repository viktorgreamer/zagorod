<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Works;
/* @var $this yii\web\View */
/* @var $model common\models\Works */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="works-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ie')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'self_cost')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

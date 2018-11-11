<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="smeta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(); ?>

    <? $existedEstimates = $model->estimatesId;
   //  \backend\utils\D::dump($existedEstimates);
    if ($estimates = \common\models\Estimate::find()->all()) {
        foreach ($estimates as $estimate) {
            $isChecked = in_array($estimate->estimate_id, $existedEstimates) ? true : false;
            echo "<br>".Html::checkbox("estimate_" . $estimate->estimate_id, $isChecked, ['label' => $estimate->name]);
        }
    } ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

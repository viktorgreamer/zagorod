<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\InputControls */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="input-controls-form">

        <?php $form = ActiveForm::begin(); ?>


        <?php echo $form->field($model, 'event_id')->widget(Select2::classname(), [
            'data' => $model->mapEvents(true),
            'options' => ['placeholder' => 'Введите название'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>


        <?php echo $form->field($model, 'input_id')->widget(Select2::classname(), [
            'data' => $model->mapInputs(true),
            'options' => ['placeholder' => 'Введите название'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>


        <?= $form->field($model, 'type')->dropDownList($model->mapTypes()) ?>

        <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$js = <<<JS

input_control_type = $(document).find('#inputcontrols-type');
input_control_value = $(document).find('#inputcontrols-value');


if (input_control_type.val() == 1) input_control_value.attr('disabled',false);
  else input_control_value.attr('disabled',true);

$(document).on('change','#inputcontrols-type',function() {
    input_control_value = $(document).find('#inputcontrols-value');
 if ($(this).val() == 1) input_control_value.attr('disabled',false);
  else input_control_value.attr('disabled',true);
});
JS;
Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);
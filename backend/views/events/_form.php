<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Events;

/* @var $this yii\web\View */
/* @var $model common\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="events-form">
        <div class="row">


            <?php $form = ActiveForm::begin(); ?>
            <div class="col-lg-3">
                <?= $form->field($model, 'estimate_id')->dropDownList(Events::mapEstimates(), ['id' => 'estimate_id']) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'parent_id')->dropDownList([0 => 'нет'] + Events::mapEvents()) ?>
            </div>
            <div class="col-lg-4">

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'type')->dropDownList(Events::mapTypes()) ?>
            </div>
            <div class="col-lg-12">
            <?php echo $this->render("_div_input_data", compact('model')); ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'formula')->textarea(['cols' => 20, 'rows' => 4]) ?>
            </div>

            <div class="col-lg-12">
                <?= $form->field($model, 'variables')->textarea(['cols' => 20, 'rows' => 4,'id' => 'variables']) ?>
            </div>


            <div class="col-lg-4">

                <?= $form->field($model, 'result')->textInput() ?>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$script = <<< JS
value = $("#estimate_id").val();
$("#add-data-to-events-formula").data('estimate_id',value);
  
$("#estimate_id").on('change', function() {
    value = $("#estimate_id").val();
    console.log(' THIS VALUE = ' + value);
  $("#add-data-to-events-formula").data('estimate_id',value);
})
// модальное окно добавления даннных в формулу
$("button.modal-button-add-data-to-formula").on('click', function (e) {
    var estimate_id = $(this).data('estimate_id');
    console.log(estimate_id);
    console.log("button.modal-button-add-data-to-formula");
    $("#modal-add-data-to-formula").modal('show').find("#modal-add-data-to-events-formula").load($(this).attr('value') + '?estimate_id='+ estimate_id);

});


// добавление input в output
$(".add-variable-to-event").on('click', function (e) {
    var input_id = $(this).data('input_id');
    

});


JS;
$this->registerJs($script, yii\web\View::POS_READY);
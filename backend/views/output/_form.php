<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model common\models\Output */
/* @var $form yii\widgets\ActiveForm */
echo Yii::$app->session->get('test_smeta_id');
?>

    <div class="output-form">
        <div class="container">


            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'estimate_id')->textInput()->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'stage_id')->textInput()->hiddenInput()->label(false) ?>

            <div class="row">
                <div class="col-lg-8">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'type')->dropDownList($model->mapTypes()) ?>

                </div>
            </div>
            <div class="row">
                <div class="container">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".data-to-formula">
                        Добавить данные в формулу
                    </button>

                    <!-- Large modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target=".bs-example-modal-lg">
                        Функции
                    </button>

                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                         aria-labelledby="myLargeModalLabel">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title" id="myModalLabel">Функции</h3>
                                </div>
                                <?php echo $this->render("functions/_functions", compact('model')); ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($smeta = \common\models\Smeta::find()->where(['forTest' => 1])->one()) {
                        echo Html::button(" Проверить на " . $smeta->name, ['class' => 'check-formula btn btn-success']);

                    } ?>

                </div>


            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger hidden" id="error-message-div"></div>
                    <div class="alert alert-warning hidden" id="warning-message-div"></div>
                    <div class="alert alert-success hidden" id="success-message-div"></div>
                    <?= $form->field($model, 'formula')->textarea(['rows' => 5]) ?>

                    <?= $form->field($model, 'result')->textInput() ?>
                </div>

                <div class="col-lg-12">
                    <?= $form->field($model, 'variables')->textarea(['cols' => 20, 'rows' => 4, 'id' => 'variables']) ?>
                </div>


                <div class="col-lg-6">
                    <?= $form->field($model, 'width')->textInput() ?>
                </div>
                <!-- --><? /*= $form->field($model, 'priority')->textInput() */ ?>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
$script = <<< JS
var formula = '';



// проверка формулы на валидность
$(".check-formula").on('click', function (e) {
    var formula = $(document).find("#output-formula").val();
    var result = $(document).find("#output-result").val();
    var type = $(document).find("#output-type").val();
    $(document).find("#error-message-div").addClass('hidden');
            $(document).find("#warning-message-div").addClass('hidden');
            $(document).find("#success-message-div").addClass('hidden');
    
        console.log(" FORMULA = " + formula + " result " + result + " TYPE " + type);
    $.ajax({
        url: '/admin/output/check',
        data: {formula: formula,result: result,type: type},
        type: 'post',
        success: function (res) {
            console.log(res);
            res = JSON.parse(res);
            console.log(res.type);
            if (res.type == 'warning') {
                toastr.warning(res.value,'Ошибка замены');
                 $(document).find("#error-message-div").text(res.eval);
            $(document).find("#error-message-div").removeClass('hidden');
            }
            if (res.type == 'success') {
                 $(document).find("#warning-message-div").text(res.eval);
            $(document).find("#warning-message-div").removeClass('hidden');
                toastr.success(res.value);
            }
            if (res.type == 'danger') {
                $(document).find("#success-message-div").text(res.eval);
            $(document).find("#success-message-div").removeClass('hidden');
                toastr.error(res.message);
              //    get_session_formula(res.type);
            }
            
          
        },

         error: function () {
           
         
             alert('ФАТАЛЬНАЯ ОШИБКА, проверьте синтаксис формулы либо приходящие данные в смете');
            
            window.formula = '';
            get_session_formula();
            
         }
    });
 
});

function get_session_formula(type = 'danger') {
     $.ajax({
        url: '/admin/output/session-formula',
        type: 'get',
        success: function (res) {
            console.log(res);
           formula = res;
           if (type == 'danger') {
                $(document).find("#error-message-div").text(formula);
            $(document).find("#error-message-div").removeClass('hidden');
           }
           if (type == 'warning') {
                $(document).find("#warning-message-div").text(formula);
            $(document).find("#warning-message-div").removeClass('hidden');
           }if (type == 'success') {
                $(document).find("#success-message-div").text(formula);
            $(document).find("#success-message-div").removeClass('hidden');
           }
         
        },
    });
}

output_id  = '$model->output_id';
// $("#div-inputs-to-output-id-" + output_id).load('/admin/output/render-inputs?output_id=' + output_id);
JS;
$this->registerJs($script, yii\web\View::POS_READY);

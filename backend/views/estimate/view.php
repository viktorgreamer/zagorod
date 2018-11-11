<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\EstimateStage;
use common\models\Icons;
use common\models\Estimate;

/* @var $this yii\web\View */
/* @var $model common\models\Estimate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны Сметы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="estimate-view container">
        <?php echo Html::a(" ВХОДЯЩИЕ ДАННЫЕ", ['estimate/view', 'id' => $model->estimate_id], ['class' => 'btn btn-primary']); ?>
        <?php echo Html::a(" РАСЧЕТ", ['estimate/view-output', 'id' => $model->estimate_id], ['class' => 'btn btn-primary']); ?>
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-lg-4">
                <?= Html::a('Редактировать', ['update', 'id' => $model->estimate_id], ['class' => 'btn btn-primary']) ?>

                <?= Html::a('Удалить', ['delete', 'id' => $model->estimate_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить смету?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?php echo Html::button(Icons::ADD . " этап",
                    [
                        'value' => \yii\helpers\Url::to(['estimate-stage/create-ajax', 'estimate_id' => $model->estimate_id, 'type' => EstimateStage::TYPE_INPUT]),
                        'class' => 'btn btn-success modal-button-create-estimate-stage',
                        'data' => [
                            "estimate_id" => $model->estimate_id],

                        //  'id' => 'modal-button-create-estimate-stage-ajax',
                        //   'data-estimate_id' => $model->estimate_id
                    ]
                ); ?>
            </div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3">
                <?php echo Html::label('Переместить в стадию', 'move_to_stage_dropdown', ['class' => 'control-label']); ?>
                <?php echo Html::dropDownList('move_to_stage', null,
                    \yii\helpers\ArrayHelper::map(EstimateStage::find()->where(['estimate_id' => $model->estimate_id])->all(), 'stage_id', 'name'),
                    [
                        'id' => 'move_to_stage_dropdown',
                        'class' => 'form-control'
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= Html::button('Ok', ['class' => 'btn btn-primary btn-success', 'id' => 'move_to_stage_button']) ?>

            </div>
        </div>






        <?php
        \yii\bootstrap\Modal::begin([
            'header' => '<h3>Добавить/редактировать этап</h3>',
            'id' => "modal-estimate-id-" . $model->estimate_id
            // 'footer' => 'Низ окна',
        ]);
        ?>
        <div id="modal-estimate-div-id-<?= $model->estimate_id; ?>"></div>
        <?php \yii\bootstrap\Modal::end(); ?>

        <? \yii\widgets\Pjax::begin(['id' => "pjax_id", 'timeout' => 5000]); ?>

        <? if ($stages = $model->stagesInput) {

            foreach ($stages as $stage) {
                if (Yii::$app->session->get('current_estimate_stage_id_admin') == $stage->stage_id) $active = true; else $active = false;
                $tabsItems[] = [
                    'label' =>   $stage->name,
                    'content' =>  $this->render('/estimate-stage/_list_view', ['model' => $stage]),
                    'active' => $active
                ];

              //  echo $this->render('/estimate-stage/_list_view', ['model' => $stage]);
            }
        }
        echo \yii\bootstrap\Tabs::widget(['items' => $tabsItems]);
        \yii\widgets\Pjax::end();
        ?>

    </div>
<?

$script = <<< JS
checked_inputs = [];
$(document).on('click','#move_to_stage_button', function() {
   if (!confirm(" Вы действительно хотите переместить поля ввода?")) return false;
    console.log("move_to_stage_button clicked");
    all_inputs = document.querySelectorAll('.multiple_select').forEach(function(item) {
     if (item.checked)  {
          // console.log("CHECKED");
         checked_inputs.push(item.dataset.input_id);
     }
    });
    console.log(all_inputs);
    console.log(checked_inputs);
    stage_id = $(document).find("#move_to_stage_dropdown").val();
    console.log(" STAGE_ID = " + stage_id);
    $.ajax({
        url: '/admin/input/move-to',
        data: {inputs:checked_inputs , stage_id: stage_id},
        type: 'post',
        success: function (res) {
            $.pjax.reload('#pjax_id',{timeout : false});
        },

        /*error: function () {
            alert('error')
        }*/
    });
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);


$js = <<<JS
not_validatable_input_type = ['4','5','6','7','8','9','10','11','12'];

$(document).on('change','#input-type',function() {

    input_type = $(this).val();
    console.log(input_type);
    is_in_array = not_validatable_input_type.indexOf(input_type);
  
if (is_in_array != -1) $(document).find("#input-validation_rule_id").attr('disabled',true);

else  $(document).find("#input-validation_rule_id").attr('disabled',false);

if (input_type == 12) document.getElementById("wiki-for-multigroup-input").classList.remove('hidden');
else document.getElementById("wiki-for-multigroup-input").classList.add('hidden');

});
JS;
Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);


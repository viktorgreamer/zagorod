<?php

use yii\helpers\Html;
use common\models\Input;
use yii\helpers\ArrayHelper;
use common\models\BaseStation;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */
/* @var $input common\models\Input */
/* @var $stage common\models\EstimateStage */
/* @var $dataProvider yii\data\ActiveDataProvider */
if ($stage->event_id) {
    //  \backend\utils\D::alert("STAGE_ID = ".$stage->stage_id.' HAS EVENT '.$stage->event_id);
    $data = " data-event_id='$stage->event_id'";
    //  \backend\utils\D::alert($data);
} else $data = '';
?>
    <div class="stage-list-view" id="div-stage-id-<?= $stage->stage_id; ?>" <?= $data; ?>>
        <div class="row">
            <!-- <div class="col-lg-12">
                <h2 class="name-stage">Этап: <? /*= $stage->name; */ ?></h2>

            </div>-->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <? if ($inputs = $stage->inputs) {
                        foreach ($inputs as $input) {
                            if ($input->is_newline) echo "</div><div class='row'>";

                                echo $this->render('_fill_input', ['model' => $input, 'variables' => $variables]);



                        }

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?= Html::Button('сохранить', [
    'class' => 'btn btn-success',
    'id' => 'submit-form-ajax-stage-id-' . $stage->stage_id,
    'data' => [
        'current_stage' => $stage->stage_id
    ]

]); ?>


<?php

$script = <<< JS

// добавление input в output
$("#submit-form-ajax-stage-id-$stage->stage_id").on('click', function (e) {
 var current_stage = $(this).data('current_stage');    
 
data = [];
  $('#div-stage-id-$stage->stage_id .input_field.active_element').each( function() {
      if ($(this).attr('type') == 'checkbox') {
          console.log(" CHECKBOX");
          if ($(this).is(":checked")) this.value = 1; else this.value = 0;
         
      }
       if (this.name) data.push({name: this.name,value: this.value});
        
      
      
  })
  
  if ( $('#div-stage-id-$stage->stage_id .multiple-input-list__item').parent().parent().parent().parent().hasClass('active_element')) {
    
  
  $('#div-stage-id-$stage->stage_id tbody tr.multiple-input-list__item').children().children().children().each( function() {
    if (this.name)  data.push({name: this.name,value: this.value,multiple:true});
  })
   }
  
  
  console.log(data);
  
   $.ajax({
        url: '/smeta/save-input',
        data: {data: data,current_stage:current_stage},
        type: 'post',
        success: function (response) {
             response  = JSON.parse('['+response + ']');
         //   console.log(response[0]);
          response.forEach(function(items, i, arr) {
              hasErrors = 0;
           items = Object.entries(items);
             items.forEach(function(item) {
                 if (item[1] !== '') hasErrors = 1; 
               /* console.log(item);
               //console.log("#error_input_id_" + item[0]);
              // console.log(item[1]);*/
               document.getElementById("#error_input_id_" + item[0]).innerText = item[1];
             })
             // item = item_array[i];
              
              //  document.getElementById("#error_input_id_" + item['key']).innerText = item['value'];
            });
          
          if (!hasErrors) {
             //   $("#div-fill-stages").load($(this).attr('value'));
          }
        },

        /* error: function () {
             alert('error')
         }*/
    });
  
 // console.log(data);
});

JS;
$this->registerJs($script, yii\web\View::POS_READY);


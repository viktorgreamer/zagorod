<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Icons;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */
/* @var $estimate_stage common\models\EstimateStage */
/* @var $estimate common\models\Estimate */
/* @var $event common\models\Events */
/* @var $dataProvider yii\data\ActiveDataProvider */

$variables = $model->loadVariables();
?>

<?php
$eventJs = [];
$startJS = '';
$js = '';

$current_stage_id = $model->current_stage;
$current_estimate_id = \common\models\EstimateStage::find()->where(['stage_id' => $current_stage_id])->one()->estimate_id;
if ($estimates = $model->estimates) {
    $itemsEstimate = [];
    foreach ($estimates as $keyEstimate => $estimate) {
        // echo "<h3>" . $estimate->name . "</h3>";
        if ($estimate_stages = $estimate->stagesInput) {
            $items = [];
            foreach ($estimate_stages as $key => $estimate_stage) {
                if (Yii::$app->user->id == 1) $label = $estimate_stage->name . " id = " . $estimate_stage->stage_id . " event_id= " . $estimate_stage->event_id;
                else $label = $estimate_stage->name;
                if (preg_match("/Трассировка/u", $label)) {
                    //  \backend\utils\D::success("ТРАССИРОВКА");
                    $items[$key] = [
                        'label' => $label,
                        'content' => \common\widgets\TraceWidget::widget(['smeta' => Yii::$app->session->get('smeta')]),
                        'linkOptions' => ['class' => 'active_element']
                    ];
                } else

                    $items[$key] = [
                        'label' => $label,
                        'content' => $this->render('_fill-stage', ['stage' => $estimate_stage, 'variables' => $variables]),
                        'linkOptions' => [
                            'data' => ['stage_id' => $estimate_stage->stage_id, 'event_id' => $estimate_stage->event_id],
                            'id' => 'stage_id_' . $estimate_stage->stage_id,
                            'class' => 'active_element'
                        ]
                    ];

                // делаем активной последнюю сохраненную вкладку
                if ($estimate_stage->stage_id == $current_stage_id) $items[$key]['active'] = true;

                // отключаем неактивные этапы по условиям
                if ($variables["event_" . $estimate_stage->event_id . "_"]) $items[$key]['linkOptions']['class'] = 'hidden';

            }

        }
        if (Yii::$app->user->id == 1) $labelEstimate = $estimate->name . " id = " . $estimate->estimate_id;
        else $labelEstimate = $estimate->name;
        $itemsEstimate[$keyEstimate] = [
            'label' => $labelEstimate,
            'content' => \yii\bootstrap\Tabs::widget(['items' => $items]),
            'linkOptions' => ['class' => 'estimate-stages']
        ];

        // делаем активной последнюю сохраненную вкладку
        if ($estimate->estimate_id == $current_estimate_id) $itemsEstimate[$keyEstimate]['active'] = true;
        if ($events = $estimate->events) {
            foreach ($events as $event) {
                $js .= $event->renderJS($model);
                // $eventJs[] = "{id:" . $event->event_id . ",name:'" . $event->name . "',value:0}";
            }
            foreach ($events as $event) {
                $startJS .= $event->renderListeners($model);
            }

        };

    }

    echo \yii\bootstrap\Tabs::widget(['items' => $itemsEstimate]);
};

/* @var $smetaEvent \common\models\SmetaEvents */
/* @var $event \common\models\Events */

if ($smetaEvents = $model->getEvents()->joinWith("event")->all()) {
    foreach ($smetaEvents as $smetaEvent) {
        if (trim($smetaEvent->event->formula)) {
            if (in_array($smetaEvent->event->type, \common\models\Events::$positive)) $type = 'show';
            else $type = 'hide';
            $eventJs[] = "{id:" . $smetaEvent->event_id . ",name:'" . $smetaEvent->event->name . "',formula:
            \"". trim($smetaEvent->event->renderFormulaForJSEvents($model)) . "\",value:" . $smetaEvent->value . ",type:'" . $type . "'}";
        }

    }

}

$input_ids = \common\models\Input::find()->where(['in', 'input_id', $model->getEstimatesId()])->select('input_id')->column();

$stage_ids = \common\models\EstimateStage::find()->where(['in', 'estimate_id', $model->getEstimatesId()])->select('stage_id')->column();
$input_ids = \common\models\Input::find()->where(['in', 'stage_id', $stage_ids])->select('input_id')->column();
$inputControls = \common\models\InputControls::find()->where(['in', 'input_id', $input_ids])->all();
/* @var $inputControl \common\models\InputControls */
if ($inputControls) {
    foreach ($inputControls as $inputControl) {
        if (!$inputControl->value) $inputControl->value = 0;
        $inputControlsJS[] = "{event_id: $inputControl->event_id,input_id: $inputControl->input_id,type: $inputControl->type,value: $inputControl->value}";
    }
    $inputControlsJS = "[" . implode(",", $inputControlsJS) . "]";
} else {
    $inputControlsJS = "[]";
}

$eventJs = "[" . implode(",", $eventJs) . "]";

echo Html::a(' РАСЧЕТ', ['smeta/calculate', 'smeta_id' => $model->smeta_id], ['class' => 'btn btn-primary']);
?>
<?php
if ($variables) {
    $varialblesBody = '';
    foreach ($variables as $name => $variable) {
        if (is_array($variable)) {
            foreach ($variable as $key => $item) {
                $varialblesBody .= "<br>" . $name . "['" . $key . "']" . " = " . $item;
            }
        } else $varialblesBody .= "<br>" . $name . " = " . $variable;
    }
} else {
    $varialblesBody = '';
}

echo \yii\bootstrap\Collapse::widget([
    'items' => [
        ['label' => 'Переменные',
            'content' => $varialblesBody,
        ],
        ['label' => 'JAVASCIPT',
            'content' => $startJS . $js,
        ],
        ['label' => 'EVENTSJS',
            'content' => "[" . $eventJs . "]",
        ],
        ['label' => 'Input-Controls',
            'content' => $inputControlsJS,
        ],
    ]]);

$station = json_encode(Yii::$app->session->get('station'));
if (!$eventJs) $eventJs = "[]";

$script = <<< JS
station = $station;
events = $eventJs;
var input_controls = $inputControlsJS;
console.log(input_controls);

true_events = events.filter( function(item) {
    return (item.value == 1)
});

console.log(true_events);

    
    
 // определение переменных
    events.forEach(function (item) {
        window['event_' + item.id + '_'] = item.value;
      //  console.log('event_' + item.id + '_' + '=' + item.value);
    });

    reload_inputs();
    reload_events();
    
    
    console.log(true_events);
    


$(document).on('change', 'input', function () {
    if ($(this).attr('type') == 'checkbox') {
        if ($(this).is(':checked')) {
            value = 1;
            window[$(this).attr('name')] = 1;
        } else {
              value = 0;
            window[$(this).attr('name')] = 0;
        }
         console.log(" name = " +  $(this).attr('name') + " value = " + value);
    } else {
        console.log(" name = " +  $(this).attr('name') + " value = " + $(this).val());
        
        value = $(this).val();
        
        if (isNumber(value)) {
                    if (parseInt(value) == value) value = parseInt(value);
                   else  value = parseFloat(value);
                } else value = value;
        
        window[$(this).attr('name')] = value;
    }
   
       
       // console.log("INPUT_2_ = " + input_2_);
        reload_events($(this).attr('name'));
});

function control_inputs(event_id) {
    window.current_event_event =  event_id;
    
     
     console.log('СОБЫТИЕ ' + window['event_' + event_id + '_']);
     
    if (window['event_' + event_id + '_'] == 1) {
        input_controls.forEach(function(item) {
            console.log('ТЕКУЩИЙ ТИП' + item.type);
            
      if (item.event_id == window.current_event_event) {
          if (item.type == 1) {
              $("#input_" + item.input_id + '_').val(item.value);
              
          }
          if (item.type == 2) {
              $("#input_" + item.input_id + '_').attr('disabled',true);
              
          }
          if (item.type == 3) {
               document.getElementById("input_" + item.input_id + '_').checked = true;
              
          }
          if (item.type == 4) {
              document.getElementById("input_" + item.input_id + '_').checked = false;
          }
      }
    });
    } else {
        input_controls.forEach(function(item) {
            console.log('ТЕКУЩИЙ ТИП' + item.type);
      if (item.event_id == window.current_event_event) {
         
          if (item.type == 2) {
              $("#input_" + item.input_id + '_').attr('disabled',false);
              
          }
          
           if (item.type == 3) {
             console.log("REMOVING ATTREBUTE CHECKED");
              document.getElementById("input_" + item.input_id + '_').checked = false;
              
          }
          if (item.type == 4) {
                document.getElementById("input_" + item.input_id + '_').checked = true;
            
              
          }
          
      }
    });
      }
    
}


function reload_inputs() {
        inputs = document.querySelectorAll('input');
        if (inputs) {
            inputs.forEach(function (item) {
                if (isNumber(item.value)) {
                    if (parseInt(item.value) == item.value) window[item.name] = parseInt(item.value);
                   else  window[item.name] = parseFloat(item.value);
                } else window[item.name] = item.value;
                console.log(" SETTING " + item.name + " VALUE " + item.value);
            })
        }

    }
    
    function isNumber(value) {
    if ((undefined === value) || (null === value)) {
        return false;
    }
    if (typeof value == 'number') {
        return true;
    }
    return !isNaN(value - 0);
}


    function reload_events(name = '') {
      
        if (name) {
              console.log(" RELOADING EVENTS ASSOCIATED WITH INPUT NAME = " + name);
            filtered_events = events.filter( function(event) {
            formula = event.formula;
           //  console.log('EVENT FORMULA = ' + event.formula);
            return formula.match(name)});
           
            filtered_events.forEach(function (item) {
            console.log(" EVENT ID = " + item.id + " FORMULA = (" + item.formula + ")");
            check_event(item); 
                });
          
            } else {
            
            events.forEach(function (item) {
               check_event(item);  
             
          //  console.log(" RESULT IS " + result);
        });

            }


        

        relatives_enents = events.filter( function(event) {
            formula = event.formula;
            // console.log('EVENT FORMULA = ' + event.formula);
            return formula.match(/event/)});

      //  console.log(" TRY TO CALCULATE EVENT RELATIVE ");

        relatives_enents.forEach(function (item) {
            check_event(item);  
        });
        
    }
    
    function check_event(item) {
    
            old_value = window['event_' + item.id + '_'];
            result = eval("(" + item.formula + ")");
            if (result == true) window['event_' + item.id + '_'] = 1;
            if (result == 0) window['event_' + item.id + '_'] = 0;
            if (result == false) window['event_' + item.id + '_'] = 0;
            if (old_value != window['event_' + item.id + '_']) {
                window.current_type = item.type;
                window.current_value = window['event_' + item.id + '_'];
                console.log(" EVENT ID = " + item.id  + " HAS CHANGED");
                 event_nodes(item.id);
                 control_inputs(item.id);
            } 
                else console.log(" EVENT ID = " + item.id  + " HAS NOT CHANGED");
    }
    
    
    function event_nodes(event_id) {
    
    nodelists =  document.querySelectorAll("[data-event_id='" + event_id + "']");

    if (nodelists) {
        //  console.log(nodelists);

        nodelists.forEach( function(item)  {
            //  console.log(item.tagName);

            console.log(" CURRENT TYPE = " + window.current_type);
            console.log(" CURRENT VALUE = " + window.current_value);

            if (window.current_type == 'show') {
                if (window.current_value == 1) {
                    item.classList.add('active_element');
                    item.classList.remove('hidden');
                }
                else {
                    item.classList.remove('active_element');
                    item.classList.add('hidden');
                }
            } else {
                if (window.current_value == 0) {
                    item.classList.remove('active_element');
                    item.classList.add('hidden');
                }
                else {
                    item.classList.add('active_element');
                    item.classList.remove('hidden');
                }
            }


        });
    }

}


JS;
$filejs = file_put_contents(Yii::getAlias('@frontend').'/web/js/smeta.js',$script);
//$this->registerJsFile('/smeta.js');
$this->registerJs($script, yii\web\View::POS_READY);



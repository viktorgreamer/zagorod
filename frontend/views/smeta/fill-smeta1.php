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
if ($inputControlsJS) $inputControlsJS = "window.input_controls = $inputControlsJS;";

$script = <<< JS
station = $station;
events = $eventJs;
$inputControlsJS;
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
    

JS;
//$filejs = file_put_contents(Yii::getAlias('@frontend').'/web/js/smeta.js',$script);
//$this->registerJsFile('/smeta.js');
$this->registerJs($script, yii\web\View::POS_READY);



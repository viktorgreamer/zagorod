<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\Events */


$model = new \common\models\Events();

if ($id) {
    if ($event = \common\models\Events::findOne($id)) {
     
    }
}
?>
<?php
/* @var $this yii\web\View */
/* @var $event common\models\Events */
/* @var $form yii\widgets\ActiveForm */


if ($event) {

        // echo "<br> event[".$event->event_id."] =".$event->stage->name. "->".$event->name;
        echo "<br>".\yii\helpers\Html::button($event->getFormulaLink(),
                ['class' => 'btn btn-success btn-xs add-event-to-formula',
                    'onclick' => "copyToClipboard('".$event->getFormulaName()."')",
                    'data' => [
                        'event_id' => $event->event_id,
                        'formula_link' => $event->getFormulaName()." =".$event->estimate->name. "->".$event->name,
                        // 'output_id' => $output_id
                    ]]);
}

$script = <<< JS

// добавление event в output
$(document).on('click', ".add-event-to-formula",function (e) {
    formula_link = $(this).data('formula_link');
    console.log(formula_link);
   document.getElementById('variables').value += formula_link + String.fromCharCode(13, 10); 

});

JS;
$this->registerJs($script, yii\web\View::POS_READY);



<?php
/* @var $this yii\web\View */
/* @var $event common\models\Events */
/* @var $form yii\widgets\ActiveForm */


if ($events) {
    foreach ($events as $event) {
        // echo "<br> input[".$input->input_id."] =".$input->stage->name. "->".$input->name;
        echo "<br>".\yii\helpers\Html::button($event->getFormulaLink(),
                ['class' => 'btn btn-success btn-xs add-input-to-formula',
                    'onclick' => "copyToClipboard('".$event->getFormulaName()."')",
                    'data' => [
                        'event_id' => $event->event_id,
                        'formula_link' => $event->getFormulaName()." =".$event->estimate->name. "->".$event->name,
                        // 'output_id' => $output_id
                    ]]);
    }
}

$script = <<< JS

// добавление input в output
$(document).on('click', ".add-input-to-formula",function (e) {
    formula_link = $(this).data('formula_link');
    console.log(formula_link);
   document.getElementById('variables').value += formula_link + String.fromCharCode(13, 10); 

});

JS;
$this->registerJs($script, yii\web\View::POS_READY);


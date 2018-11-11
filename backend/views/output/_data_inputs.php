<?php
/* @var $this yii\web\View */
/* @var $input common\models\Input */
/* @var $form yii\widgets\ActiveForm */


if ($inputs) {
    foreach ($inputs as $input) {
        // echo "<br> input[".$input->input_id."] =".$input->stage->name. "->".$input->name;
        echo "<br>".\yii\helpers\Html::button($input->getFormulaLink(),
            ['class' => 'btn btn-success btn-xs add-input-to-formula',
                'data' => [
                    'input_id' => $input->input_id,
                    'formula_link' => $input->getFormulaName()." =".$input->stage->name. "->".$input->name,
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


<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\Output */


$model = new \common\models\Output();

$name = '';
$link = "output_{id}_.";
if ($id) {
    if ($output = \common\models\Output::findOne($id)) {
     
    }
}
?>
<?php
/* @var $this yii\web\View */
/* @var $output common\models\Output */
/* @var $form yii\widgets\ActiveForm */


if ($output) {

        // echo "<br> output[".$output->output_id."] =".$output->stage->name. "->".$output->name;
        echo "<br>".\yii\helpers\Html::button($output->getFormulaLink(),
                ['class' => 'btn btn-success btn-xs add-output-to-formula',
                    'onclick' => "copyToClipboard('".$output->getFormulaName()."')",
                    'data' => [
                        'output_id' => $output->output_id,
                        'formula_link' => $output->getFormulaName()." =".$output->stage->name. "->".$output->name,
                        // 'output_id' => $output_id
                    ]]);
}

$script = <<< JS

// добавление output в output
$(document).on('click', ".add-output-to-formula",function (e) {
    formula_link = $(this).data('formula_link');
    console.log(formula_link);
   document.getElementById('variables').value += formula_link + String.fromCharCode(13, 10); 

});

JS;
$this->registerJs($script, yii\web\View::POS_READY);



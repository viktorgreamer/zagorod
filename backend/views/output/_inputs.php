<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $output common\models\Output */

?>
<?php
if ($inputs = $output->inputs) {
    $body = [];
    foreach ($inputs as $input) {
        $body[] = "input[" . $input->input_id . "] =" . $input->stage->name . "->" . $input->name;
    }
    echo implode("\r\n", $body);
}

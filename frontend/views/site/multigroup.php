<?php

use yii\widgets\ActiveForm;
use backend\utils\D;

use unclead\multipleinput\MultipleInput;

?>

<?php


// \backend\utils\D::dump($_POST);


$smeta_id = 1;
$smeta = new \common\models\Smeta();
$smeta->smeta_id = 1;
$smeta->estimate_id = 5;

$input = new \common\models\Input(['input_id' => 356]);




$input_356_ = $_POST['input_356_'];
\common\models\InputValue::deleteAll(['smeta_id' => $smeta->smeta_id, 'input_id' => $input->input_id]);

if (is_array($input_356_)) {
    $inputValue = \common\models\InputValue::set($smeta, $input, $input_356_);
}


$form = ActiveForm::begin();

if ($input_356_ = \common\models\InputValue::find()->where(['smeta_id' => 1])->andWhere(['input_id' => 356])->one()) {
   // D::dump($input_356_->toArray());
    if ($value = $input_356_->value) {
        if (preg_match('/\{.+\}/', $value)) {
           //  D::success(" COMES SERIALIZED ARRAY ");
            $value = unserialize($value);
            D::dump(eval('return $value[0][\'user\'];'));
        }

    }
}


$columns = [];
$template = "name=user:type=dropDownList:items=Один,Два:title=Пользователь|name=priority:title=Приоритет";
// расшифровка закодированных параметров
if ($names = explode("|", $template)) {
    foreach ($names as $key => $name) {
        if ($params = explode(":", $name)) {
            foreach ($params as $param) {

                if ($options = explode("=", $param)) {
                    if (preg_match("/,/", $options[1])) {
                        foreach (explode(",", $options[1]) as $item) {
                            $array[$item] = $item;
                        }
                        $columns[$key][$options[0]] = $array;
                        //  $columns[$key]['data'] =
                    } else {
                        $columns[$key][$options[0]] = $options[1];
                    }


                }
            }
        }

    }

}

// \backend\utils\D::dump($columns);


echo MultipleInput::widget([
    'data'=> $value,
    'max' => 4,
    'name' => 'input_356_',
    'columns' => $columns
]);

echo \yii\helpers\Html::submitButton(" SUBMIT");

\yii\widgets\ActiveForm::end();



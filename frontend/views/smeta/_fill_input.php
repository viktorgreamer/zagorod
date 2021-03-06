<?php

use yii\helpers\Html;
use common\models\Input;
use yii\helpers\ArrayHelper;
use common\models\BaseStation;
use common\models\Icons;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model common\models\Input;
 *
 */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $control \common\models\InputControls */

?>

<?php
$smeta = Yii::$app->session->get('smeta');
$inputValue = \common\models\InputValue::find()->where(['smeta_id' => $smeta->smeta_id])->andWhere(['input_id' => $model->input_id])->one();
if ($inputValue) $value = $inputValue->value; else $value = '';

if ($model->event_id) $data = ['event_id' => $model->event_id];
$title = $input->input_id;
if ($controls = $model->controls) {

    foreach ($controls as $control) {
        $title .= "CONTROL_ID " . $control->id . " TYPE = " . $control->type;
        if ($event_value = \common\models\SmetaEvents::find()->where(['smeta_id' => $smeta->smeta_id])->andWhere(['event_id' => $control->event_id])->one()) {
            if ($event_value->value) {
                if ($control->type == \common\models\InputControls::TYPE_SET_VALUE) $value = $control->value;
                if ($control->type == \common\models\InputControls::TYPE_CHECKED) $value = 1;
                if ($control->type == \common\models\InputControls::TYPE_DISACTIVE) $disable = true;
                if ($control->type == \common\models\InputControls::TYPE_UNCHECKED) $value = 0;

            }
        }
    }
}


if ($model->event_id) {
    $title .= "EVENT_ID = " . $model->event_id . " FOR " . $model->getFormName() . " IS " . $variables["event_" . $model->event_id . "_"];

    if ($variables["event_" . $model->event_id . "_"]) {
        $title .= " ELEMENT " . $model->getFormName() . " IS ACTIVE";
        $classVisible = 'active_element';
    } else {
        $title .= " ELEMENT  " . $model->getFormName() . " IS HIDDEN";
        $classVisible = 'hidden';
    }
} else $classVisible = 'active_element';

$inputClass = 'input_field ' . $classVisible;
$id = $model->getFormID();
?>
    <div class="input-list-view <?= $classVisible; ?>" id="div-input-id-<?= $model->input_id; ?>"
         style="background-color: #e1e1e1" data-event_id=<?= $model->event_id; ?>>
        <?php if ($model->type == Input::HEADER_1) {
            echo "<h4 style='margin-left: 50px;'> " . $model->name . "</h4>";
        } else { ?>
            <div class="<?php echo $model->colClass; ?>">
                <div class="form-group">
                    <?php if (!in_array($model->type, [Input::BOOLEAN_TYPE])) echo Html::label($model->name, "#" . $id, ['data' => $data]); ?>


                    <?php if (in_array($model->type, [Input::STRING_TYPE, Input::FLOAT_TYPE, Input::INTEGER_TYPE])) {

                        if ($model->multiple) {
                            echo "<div class='$inputClass'>";
                            $multipleOptions = [
                                'name' => $model->getFormName(),
                                'data' => $model->getGroupValue($value),
                                'addButtonOptions' => [
                                    'class' => ' btn btn-success', 'id' => 'button-add-multiple']

                            ];
                            echo MultipleInput::widget($multipleOptions);
                            echo "</div>";
                        } else {
                            echo Html::input('text', $model->getFormName(), $value,
                                [
                                    'id' => $id,
                                    'size' => $model->width,
                                    'width' => $model->width,
                                    'class' => $inputClass . ' form-control',
                                    'maxlength' => $model->width,
                                    'data' => $data,
                                    'disabled' => $disable

                                ]
                            );
                        }

                    } elseif ($model->type == Input::IN_LIST_BASE_STATION) {
                        echo " ДОСТУПНО ".count(BaseStation::getAvailableForCurrent());
                        echo Html::dropDownList($model->getFormName(), $value, [0 => ''] + ArrayHelper::map(BaseStation::getAvailableForCurrent(), 'id', 'name'),
                            ['class' => $inputClass . ' form-control', 'id' => $id, 'data' => $data, 'disabled' => $disable]);
                    } elseif ($model->type == Input::IN_LIST_MATERIAL) {
                        echo Html::dropDownList($model->getFormName(), $value, ArrayHelper::map(\common\models\Material::find()->all(), 'id', 'name'), [
                            'class' => $inputClass . ' form-control', 'id' => $id, 'data' => $data, 'disabled' => $disable]);
                    } elseif ($model->type == Input::IN_LIST_OF_CITIES) {
                        echo Html::dropDownList($model->getFormName(), $value, ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'), [
                            'class' => $inputClass . ' form-control', 'id' => $id, 'data' => $data, 'disabled' => $disable]);
                    } elseif ($model->type == Input::IN_LIST_OF_MANAGERS) {
                        echo Html::dropDownList($model->getFormName(), $value, ArrayHelper::map(\common\models\User::find()->all(), 'id', 'fullName'), [
                            'class' => $inputClass . ' form-control', 'id' => $id, 'data' => $data, 'disabled' => $disable]);
                    } elseif ($model->type == Input::BOOLEAN_TYPE) {
                        if ($value) $value = true; else $value = false;
                        echo Html::checkbox($model->getFormName(), $value, ['label' => $model->name, 'class' => $inputClass, 'id' => $id, 'data' => $data, 'disabled' => $disable, 'title' => $title]);
                    } elseif ($model->type == Input::IN_ARRAY_TYPE) {
                        $items = $model->renderItems($model->list);
                        echo Html::dropDownList($model->getFormName(), $value, $items,
                            ['label' => $model->name, 'class' => $inputClass . ' form-control', 'title' => $title, 'id' => $id,
                                'data' => $data, 'disabled' => $disable]);
                    } elseif ($model->type == Input::GROUP_INPUT) {
                        echo "<div class='$inputClass'>";
                       // \backend\utils\D::success($model->input_id);
                       // \backend\utils\D::dump($model->getColumnsSchema());
                       echo \common\widgets\MultipleInput::widget(['input' => $model]);
                        echo "</div>";
                    }

                    ?>
                    <?php echo Html::tag('div', '', ['id' => "#error_input_id_" . $model->input_id, 'class' => 'validation_error_message', 'data' => $data]); ?>

                </div>
            </div>
        <? } ?>

    </div>


<?php

$formName = $model->getFormName();
if ($mask = $model->validationRule->mask) {
    $script = <<< JS
$(document).find("input[name^=$formName]").mask('$mask');
/*$(document).on('blur',"#button-add-multiple",function() {
    console.log("js-input-plus");
  $(document).find("input[name^=$formName]").mask('$mask');
});*/

$('.multiple-input').on('afterAddRow', function(e, row) {
   $(document).find("input[name^=$formName]").mask('$mask');
});




JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}



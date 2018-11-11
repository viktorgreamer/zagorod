<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\InputSearch */
/* @var $model common\models\Input */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use common\models\Input;
use common\models\Icons;
use common\models\BaseStation;
use yii\helpers\ArrayHelper;
use unclead\multipleinput\MultipleInput;


?>

<div class="input-list-view" id="div-input-id-<?= $model->input_id; ?>" style="background-color: #e1e1e1">
    <div class="row">
        <div class="col-lg-1">
            <?= $model->formulaName; ?>
        </div>
        <div class="col-lg-9">
                <div class="form-group">
                    <?php if (!in_array($model->type, [Input::BOOLEAN_TYPE])) echo Html::label($model->name, "#".$model->formID); ?>
                    <?php

                    if (in_array($model->type, [Input::STRING_TYPE, Input::FLOAT_TYPE, Input::INTEGER_TYPE])) {
                        if ($model->multiple) {
                        echo MultipleInput::widget(
                                [
                                        'name' => $model->getFormulaName(),

                            'enableGuessTitle'  => true,
                            ]);
                        } else  {
                            if ($model->width > 249)
                                echo Html::textarea('', '', ['class' => 'form-control', 'cols' => 70, 'rows' => '4', 'id' => $model->formID,]);

                            else echo Html::input('string', '', '',
                                [
                                    'id' => $model->formID,
                                    'size' => $model->width,
                                    'width' => $model->width,
                                    'class' => 'form-control',
                                    'maxlength' => $model->width

                                ]
                            );
                        }

                    } elseif ($model->type == Input::IN_LIST_BASE_STATION) {
                        echo Html::dropDownList('', '', ArrayHelper::map(BaseStation::find()->all(), 'id', 'name'), ['class' => 'form-control','id' => $model->formID]);
                    } elseif ($model->type == Input::IN_LIST_MATERIAL) {
                        echo Html::dropDownList('', '', ArrayHelper::map(\common\models\Material::find()->all(), 'id', 'name'), ['class' => 'form-control','id' => $model->formID]);
                    } elseif ($model->type == Input::IN_LIST_OF_CITIES) {
                        echo Html::dropDownList($model->getFormName(), $value, ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'), ['class' => $inputClass . ' form-control', 'id' => $id, 'data' => $data,]);
                    }elseif ($model->type == Input::IN_LIST_OF_MANAGERS) {
                        echo Html::dropDownList('', '', ArrayHelper::map(\common\models\User::find()->all(), 'id', 'fullName'), ['class' => 'form-control','id' => $model->formID]);
                    } elseif ($model->type == Input::BOOLEAN_TYPE) {
                        echo Html::checkbox('', false, ['label' => $model->name,'id' => $model->formID]);
                    } elseif ($model->type == Input::IN_ARRAY_TYPE) {
                        $items = $model->renderItems($model->list);
                        echo Html::dropDownList('', '', $items, ['label' => $model->name, 'class' => $inputClass .' form-control','id' => $model->formID, 'data' => $data]);
                    }

                    ?>



                </div>
        </div>
        <div class="col-lg-2">
                <?= $this->render('_action_buttons', compact('model')); ?>
        </div>
    </div>


</div>

<?php
$formName = $model->getFormName();
if ($mask = $model->validationRule->mask) {
    $script = <<< JS
// $(document).find("input[name^=$formName]").mask('$mask');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}


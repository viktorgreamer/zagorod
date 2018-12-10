<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Events;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model common\models\Input */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="input-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'name')->textInput() ?>

            </div>

            <div class="col-lg-12">
                <?= $form->field($model, 'stage_id')->textInput()->hiddenInput()->label(false) ?>

                <?= $form->field($model, 'type')->dropDownList($model->mapTypes()) ?>

                <?php
                echo $this->render('_multi-input',['form' => $form,'model' => $model]); ?>

                <div class="alert alert-danger hidden" id="wiki-for-multigroup-input">

                    <ul>
                        <li>
                            Новое поле с новой строки!
                        </li>
                        <li>
                            Допустимые параметры: name,rule,type,items,title
                        </li>
                        <li>
                            name - название поля в формулах (уникальное для группы), латинницей без пробелов
                        </li>
                        <li>
                            rule - уникальный номер (id) правила валидации
                        </li>
                        <li>
                            type - тип поля ввода, допустимое значения:dropDownList - список,(по умолчанию текст)
                        </li>
                        <li>
                            items - Список значений для поля типа dropDownList
                            <br> например (,Да,Нет) выведет
                            <br> <?php echo Html::dropDownList('noname', 0, ['', 'Да', 'Нет'], ['class' => 'form-control']); ?>
                        </li>
                        <li>
                            title - Название для поля ввода
                        </li>
                        <li>
                            Пример:
                            </br>name=user:type=dropDownList:items=Один,Два:title=Пользователь
                            name=priority:rule=2:title=Приоритет
                        </li>
                    </ul>
                </div>
                <?= $form->field($model, 'list')->textarea() ?>

                <?= $form->field($model, 'validation_rule_id')->dropDownList([0 => 'Нет'] + $model->mapRules()) ?>

            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'multiple')->checkbox() ?>

            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'required')->checkbox() ?>

            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'is_newline')->checkbox() ?>

            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'width')->dropDownList($model->mapWidth()) ?>

            </div>
        </div>

        <?= $form->field($model, 'event_id')->dropDownList([0 => 'нет'] + Events::mapEvents()) ?>

        <div class="form-group">
            <?php echo Html::a('Создать множественное поля ввода', ['input/create-multiple-input', 'stage_id' => $model->stage_id], ['target' => '_blank']); ?>

            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php


$js = <<<JS
input_type = document.getElementById('input-type').value;

if (not_validatable_input_type.indexOf(input_type) != -1) document.getElementById("input-validation_rule_id").disabled = true; 
 else document.getElementById("input-validation_rule_id").disabled = false; 
if (input_type == 12) document.getElementById("wiki-for-multigroup-input").classList.remove('hidden');
else document.getElementById("wiki-for-multigroup-input").classList.add('hidden');

JS;
$this->registerJs($js, yii\web\View::POS_READY);

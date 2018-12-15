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

                <?= $form->field($model, 'type')->hiddenInput()->label(false); ?>

                <?php
                echo $this->render('_multi-input',['form' => $form,'model' => $model]); ?>

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
            <div class="col-lg-6">
                <?= $form->field($model, 'max')->textInput() ?>

            </div>
        </div>

        <?= $form->field($model, 'event_id')->dropDownList([0 => 'нет'] + Events::mapEvents()) ?>

        <div class="form-group">

            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php

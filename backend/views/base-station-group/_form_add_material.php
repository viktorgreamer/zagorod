<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStationGroup */
/* @var $materialToGroup common\models\MaterialToGroup */

$materialToGroup->articul = '';
$materialToGroup->count = 1;
?>

<h3> Добавить товар в группу </h3>
<div class="base-station-group-form">
    <div class="rows">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($materialToGroup, 'group_id')->hiddenInput()->label(false); ?>
        <div class="col-lg-3">
            <?= $form->field($materialToGroup, 'articul')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($materialToGroup, 'count')->textInput() ?>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

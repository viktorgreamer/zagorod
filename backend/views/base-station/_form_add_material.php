<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStationGroup */
/* @var $materialToStation common\models\MaterialToStation */

$materialToStation->articul = '';
$materialToStation->count = 1;
?>

<h3> Добавить товар в станцию </h3>
<div class="base-station-group-form">
    <div class="rows">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($materialToStation, 'station_id')->hiddenInput()->label(false); ?>
        <div class="col-lg-3">
            <?= $form->field($materialToStation, 'articul')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($materialToStation, 'count')->textInput() ?>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

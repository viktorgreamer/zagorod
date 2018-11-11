<?php
use yii\helpers\Html;

use common\models\Icons; ?>




<div class="edit-group" align="right" style="padding-right: 0px;">
<?= Html::button(Icons::EDIT,
    [
        'value' => \yii\helpers\Url::to(['estimate-stage/update-ajax', 'stage_id' => $model->stage_id]),
        'class' => 'btn btn-success modal-button-update-estimate-stage btn-xs',
        'data' => [
            "estimate_id" => $model->estimate_id],
    ]
); ?>
<?= Html::button(Icons::ADD . " поле ввода",
    [
        'value' => \yii\helpers\Url::to(['input/create-ajax', 'stage_id' => $model->stage_id]),
        'class' => 'btn btn-success btn-xs modal-button-create-stage-input-ajax',
        'data' => [
            "stage_id" => $model->stage_id,

        ],

    ]
); ?>
<?= Html::button(Icons::MOVE_UP, ['class' => 'btn btn-success stage-priority-change btn-xs',
    'data' => [
        'stage_id' => $model->stage_id,
        'priority' => 'up'
    ]]); ?>
<?= Html::button(Icons::MOVE_DOWN, ['class' => 'btn btn-success stage-priority-change btn-xs',
    'data' => [
        'stage_id' => $model->stage_id,
        'priority' => 'down'
    ]]); ?>
<?= Html::button(Icons::REMOVE, ['class' => 'btn btn-danger delete-stage btn-xs',
    'data' => [
        'stage_id' => $model->stage_id,
        "status_name" => 'status',
        "status" => \common\models\EstimateStage::STATUS_DISACTIVE,
    ]]); ?>

</div>
<?php
    \yii\bootstrap\Modal::begin([
    'header' => Html::tag('h4', "Добавить/редактировать поле ввода в '" . $model->name . "'"),
    'id' => "modal-input-ajax-id-" . $model->stage_id
    // 'footer' => 'Низ окна',
    ]);
    ?>
    <div id="div-create-stage-input-ajax-stage-id-<?= $model->stage_id; ?>"></div>
<?php \yii\bootstrap\Modal::end(); ?>
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
        <?= Html::button(Icons::ADD . "  вывод",
            [
                'value' => \yii\helpers\Url::to(['output/create-ajax', 'stage_id' => $model->stage_id]),
                'class' => 'btn btn-success btn-xs modal-button-create-stage-output-ajax',
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




<?

use yii\helpers\Html;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model \common\models\Input */


?>

<div align="right">


    <?= Html::button(Icons::REMOVE, ['class' => 'btn btn-danger delete-row-button btn-xs',
        'data' => ['tr_id' => $tr_id]
    ]); ?>
<?php if ($tr_id == $max_row) $disable_down_priority_button = true; else $disable_down_priority_button = false; ?>
<?php if ($tr_id == 1) $disable_up_priority_button = true; else $disable_up_priority_button = false; ?>
    <?= Html::button(Icons::MOVE_UP, ['class' => 'btn btn-success row-priority-change btn-xs',
        'disabled' => $disable_up_priority_button,
        'data' => [
            'priority' => 'up',
            'tr_id' => $tr_id,
            'pjax' => '1',
            'pjax-timeout' => 5000,
        ]]); ?>
    <?= Html::button(Icons::MOVE_DOWN, ['class' => 'btn btn-success row-priority-change btn-xs',
        'disabled' => $disable_down_priority_button,
        'data' => [
            'priority' => 'down',
            'pjax' => '1',
            'tr_id' => $tr_id,
            'pjax-timeout' => 5000,
        ]]); ?>

    <?= Html::button(Icons::COPY, ['class' => 'btn btn-primary copy-row-to-output btn-xs',
        'title' => 'Копировать строку']); ?>
</div>
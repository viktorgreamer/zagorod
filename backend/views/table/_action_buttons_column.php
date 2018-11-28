<?

use yii\helpers\Html;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model \common\models\Input */


?>

<div align="right">




    <?= Html::button(Icons::MOVE_LEFT, ['class' => 'btn btn-primary column-width-change btn-xs',
        'data' => [
            'width' => '-20',
            'td_id' => $td_id,
            'pjax' => '1',
            'pjax-timeout' => 5000,
        ]]); ?>
    <?= Html::button(Icons::MOVE_RIGHT, ['class' => 'btn btn-primary column-width-change btn-xs',
        'data' => [
            'width' => '20',
            'pjax' => '1',
            'td_id' => $td_id,
            'pjax-timeout' => 5000,
        ]]); ?>

    <?= Html::button(Icons::REMOVE, ['class' => 'btn btn-danger delete-column-button btn-xs',
        'data' => ['td_id' => $td_id]
    ]); ?>


    <?php if ($td_id == $max_column) $disable_down_priority_button = true; else $disable_down_priority_button = false; ?>
    <?php if ($td_id == 1) $disable_up_priority_button = true; else $disable_up_priority_button = false; ?>
    <?= Html::button(Icons::MOVE_LEFT, ['class' => 'btn btn-success column-priority-change btn-xs',
        'disabled' => $disable_up_priority_button,
        'data' => [
            'priority' => 'left',
            'td_id' => $td_id,
            'pjax' => '1',
            'pjax-timeout' => 5000,
        ]]); ?>
    <?= Html::button(Icons::MOVE_RIGHT, ['class' => 'btn btn-success column-priority-change btn-xs',
        'disabled' => $disable_down_priority_button,
        'data' => [
            'priority' => 'right',
            'pjax' => '1',
            'td_id' => $td_id,
            'pjax-timeout' => 5000,
        ]]); ?>

</div>
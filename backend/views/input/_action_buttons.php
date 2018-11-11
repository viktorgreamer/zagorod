<?

use yii\helpers\Html;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model \common\models\Input */


?>

<div align="right">

    <?php echo Html::checkbox('multuple_select_' . $model->getFormID(), null,
        [
            'data' => ['input_id' => $model->input_id],
            'class' => 'multiple_select']); ?>
    <?php echo Html::button(Icons::EDIT,
        [
            'value' => \yii\helpers\Url::to(['input/update-ajax', 'id' => $model->input_id]),
            'class' => 'btn btn-success btn-xs modal-button-update-stage-input-ajax',
            'data' => [
                'stage_id' => $model->stage_id
            ],

        ]
    ); ?>

    <?= Html::button(Icons::REMOVE, ['class' => 'btn btn-danger delete-input btn-xs',
        'data' => [
            'input_id' => $model->input_id
        ]]); ?>

    <?= Html::button(Icons::MOVE_UP, ['class' => 'btn btn-success input-priority-change btn-xs',
        'data' => [
            'input_id' => $model->input_id,
            'priority' => 'up',
            'pjax' => '1',
            'pjax-timeout' => 5000,
        ]]); ?>
    <?= Html::button(Icons::MOVE_DOWN, ['class' => 'btn btn-success input-priority-change btn-xs',
        'data' => [
            'input_id' => $model->input_id,
            'priority' => 'down',
            'pjax' => '1',
            'pjax-timeout' => 5000,
        ]]); ?>

    <?= Html::button(Icons::COPY, ['class' => 'btn btn-primary copy-input-to-output btn-xs',
        'data' => [
            'input_id' => $model->input_id,
        ]
        , 'title' => 'Копировать в Вывод данных']); ?>
</div>
<?

use yii\helpers\Html;
use common\models\Icons;


/* @var $this yii\web\View */
/* @var $model common\models\Output */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div align="right">
    <?php echo Html::button(Icons::EDIT,
        [
            'value' => \yii\helpers\Url::to(['output/update-ajax', 'id' => $model->output_id]),
            'class' => 'btn btn-success btn-xs modal-button-update-stage-output-ajax',
            'data' => [
                'stage_id' => $model->stage_id,
                'output_id' => $model->output_id
            ],

        ]
    ); ?>

    <?= Html::button(Icons::REMOVE, ['class' => 'btn btn-danger delete-output btn-xs',
        'data' => [
            'output_id' => $model->output_id
        ]]); ?>

    <?= Html::button(Icons::MOVE_UP, ['class' => 'btn btn-success output-priority-change btn-xs',
        'data' => [
            'output_id' => $model->output_id,
            'priority' => 'up'
        ]]); ?>
    <?= Html::button(Icons::MOVE_DOWN, ['class' => 'btn btn-success output-priority-change btn-xs',
        'data' => [
            'output_id' => $model->output_id,
            'priority' => 'down'
        ]]); ?>
</div>
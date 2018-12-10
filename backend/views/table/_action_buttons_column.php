<?

use yii\helpers\Html;
use common\models\Icons;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $model \common\models\Input */


?>

<div align="center">
  <?=  $column['hidden'] ? Icons::CLOSE_EYE : Icons::EYE; ?>
    <div class="btn-group">
         <div class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Ширина <?= round($width/6); ?> символов ">
            <?= $column_address; ?> <span class="caret"></span>
        </div>
        <ul class="dropdown-menu">
            <li> <?= Html::button(Icons::MOVE_LEFT, ['class' => 'btn btn-primary column-width-change btn-xs',
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
                    'data' => [
                        'td_id' => $td_id,
                    ]
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

                <?= Html::button(Icons::SELECT, ['class' => 'btn btn-primary select-column btn-xs',
                    'title' => 'Выделить столбец',
                    'data' => [
                        'pjax' => '1',
                        'td_id' => $td_id,
                        'pjax-timeout' => 5000,
                    ]]);  ?>


                <?= Html::button($column['hidden'] ? Icons::EYE : Icons::CLOSE_EYE, ['class' => 'btn btn-primary toggle-column-visibility btn-xs',
                    'title' => $column['hidden'] ? 'Показывать клиентам' : 'Непоказывать клиентам',
                    'data' => [
                        'pjax' => '1',
                        'td_id' => $td_id,
                        'pjax-timeout' => 5000,
                    ]]);  ?>
            </li>
        </ul>
    </div>
</div>


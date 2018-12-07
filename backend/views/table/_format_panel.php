<?php
use yii\helpers\Html;
use common\models\TableCells;
use common\models\Icons;
use common\models\TableHistory;

echo Html::button("+ROW", ['id' => 'add-row-button', 'class' => 'btn btn-success btn-xs','title' => "Добавить строку"]);
echo Html::button("+COLUMN", ['id' => 'add-column-button', 'class' => 'btn btn-success btn-xs','title' => "Добавить столбец"]);
echo Html::button(" Объеденить", ['id' => 'combine', 'class' => 'btn btn-primary btn-xs','title' => "Объеденить ячейки"]);
echo Html::button(" H1", ['class' => 'btn btn-primary format btn-xs', 'title' => "Заголовок",'data' => ['format_type' => TableCells::H1]]);
echo Html::button(" H4", ['class' => 'btn btn-primary format btn-xs','title' => "Маленький заголовок", 'data' => ['format_type' => TableCells::H4]]);
echo Html::button(Icons::ALIGH_LEFT, ['class' => 'btn btn-primary format btn-xs','title' => "Выровнить влево", 'data' => ['format_type' => TableCells::LEFT]]);
echo Html::button(Icons::ALIGH_CENTER, ['class' => 'btn btn-primary format btn-xs', 'title' => "Выровнить по центру",'data' => ['format_type' => TableCells::CENTER]]);
echo Html::button(Icons::ALIGH_RIGHT, ['class' => 'btn btn-primary format btn-xs', 'title' => "Выровнить вправо",'data' => ['format_type' => TableCells::RIGHT]]);
echo Html::button(Icons::TEXT_BOLD, ['class' => 'btn btn-primary format btn-xs', 'title' => "Сделать жирный текст",'data' => ['format_type' => TableCells::BOLD]]);
echo Html::button(Icons::REMOVE, ['class' => 'btn btn-danger format btn-xs', 'title' => "Очистить форматирование",'data' => ['format_type' => '']]);
echo Html::button('Сумма', ['class' => 'btn btn-success render_sum btn-xs', 'title' => "Суммировать ящейки"]);
echo Html::button('Текст', ['class' => 'btn btn-primary set_type btn-xs', 'title' => "Тип текст",'data' => ['type' => TableCells::TEXT_TYPE]]);
echo Html::button('Формула', ['class' => 'btn btn-primary set_type btn-xs', 'title' => "Суммировать ящейки",'data' => ['type' => TableCells::FORMULA_TYPE]]);
?>


<div class="btn-group">
  <button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    История <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" id = "history-list">
      <? $histories = TableHistory::Last();
      if ($histories) {
          foreach ($histories as $history) {
              echo Html::tag('li',Html::a($history->name,"#",['class' => 'choose_history','data' => ['table_history_id' => $history->id,'title' => $history->timeText]]));
          }
      }?>
  </ul>
</div>

<?php
use yii\helpers\Html;
use common\models\TableCells;
use common\models\Icons;

echo Html::button("+ROW", ['id' => 'add-row-button', 'class' => 'btn btn-success']);
echo Html::button("+COLUMN", ['id' => 'add-column-button', 'class' => 'btn btn-success']);
echo Html::button(" Объеденить", ['id' => 'combine', 'class' => 'btn btn-primary']);
echo Html::button(" H1", ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::H1]]);
echo Html::button(" H4", ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::H4]]);
echo Html::button(Icons::ALIGH_LEFT, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::LEFT]]);
echo Html::button(Icons::ALIGH_CENTER, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::CENTER]]);
echo Html::button(Icons::ALIGH_RIGHT, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::RIGHT]]);
echo Html::button(Icons::TEXT_BOLD, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::BOLD]]);

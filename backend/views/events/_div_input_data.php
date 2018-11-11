
<?php
use yii\helpers\Html;
use common\models\Icons;

echo Html::button(Icons::ADD . " Данные",
    [
        'value' => \yii\helpers\Url::to(['events/add-data-to-formula']),
        'class' => 'btn btn-success btn-xs modal-button-add-data-to-formula',
        'data' => [
            "estimate_id" => $model->estimate_id
        ],
        'id' => 'add-data-to-events-formula'
    ]
); ?>

<?php
\yii\bootstrap\Modal::begin([
    'header' => '<h3>Добавить данные в формулу</h3>',
    'id' => "modal-add-data-to-formula",
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
    // 'footer' => 'Низ окна',
]);
?>
<div id="modal-add-data-to-events-formula"></div>
<?php \yii\bootstrap\Modal::end(); ?>
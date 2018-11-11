
<?php
use yii\helpers\Html;
use common\models\Icons;
?>


<?php
/*echo Html::button(Icons::ADD . " Данные",
    [
        'value' => \yii\helpers\Url::to(['output/add-data-to-formula', 'estimate_id' => $model->estimate_id, 'output_id' => $model->output_id]),
        'class' => 'btn btn-primary modal-button-add-data-to-formula',
        'data' => [
            "output_id" => $model->output_id],
    ]
); */?><!--

        <?php
/*        \yii\bootstrap\Modal::begin([
            'header' => '<h3>Добавить данные в формулу</h3>',
            'id' => "modal-add-data-to-formula",
            'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            // 'footer' => 'Низ окна',
        ]);
        */?>
    <div id="modal-add-data-to-formula-div-id-<?/*= $model->output_id; */?>"></div>
<?php /*\yii\bootstrap\Modal::end(); */?>


-->

<!-- Large modal -->


<div class="modal fade data-to-formula" role="dialog"  aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Переменные, которые можно добавить в формулу</h4>
            </div>
<?php

echo $this->render("_data",compact('inputs'));

?>

        </div>
    </div>
</div>

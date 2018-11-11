<?php

use common\models\TableCells;
use \backend\utils\D;
use yii\helpers\Html;


$table_id = 1;
$query_row = TableCells::find()->where(['table_id' => $table_id])->select('tr_id');
$rows = $query_row->distinct()->asArray()->all();

echo Html::tag("h3","ТАБЛИЦА");
if ($rows) {
    $trs = '';
    foreach ($rows as $row) {

        $row_sells_query = TableCells::find()->where(['table_id' => $table_id])->andWhere(['tr_id' => $row['tr_id']]);
        if ($cells = $row_sells_query->asArray()->all()) {
            $tds = '';
            foreach ($cells as $cell) {

                $value = Html::tag('div',$cell['value'],  ['class' => 'edit-cell','title' => 'Комментарий',
                        'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]])
                    .Html::input('text', '', $cell['value'],
                        ['class' => 'edit-title-input hidden'
                        ]).Html::button('ok',['class' => 'btn btn-success btn-xs hidden input-ok',
                        ['data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                    ]);
                $tds .= Html::tag('td', $value);
            }
        }

       $trs .= Html::tag('tr',$tds);
    }
    $table = Html::tag("table",$trs,['class' => 'table table-stripped table-bordered']);
}
echo $table;



?>


<?php

$js = <<<JS
$(document).on("click",".edit-cell", function () {
    window.tr_id = $(this).data('tr_id');
    window.td_id = $(this).data('td_id');
    console.log("CELL " + window.td_id);
    console.log("ROW " + window.tr_id);
    $(this).parent().find("input").removeClass('hidden');
    $(this).parent().find("button").removeClass('hidden');
    $(this).addClass('hidden');
   
});

$(document).on("click",".input-ok", function () {
    tr = $(this).parent(); 
     tr_id = tr.find("div").data('tr_id');
    td_id = tr.find("div").data('td_id');
   tr.find("div").text(tr.find("input").val());
    
    tr.find("div").removeClass('hidden');
    tr.find("input").addClass('hidden');
    tr.find("button").addClass('hidden');
       $.ajax({
        url: '/admin/table-cells/change',
        data: {attr: 'value',value: tr.find("input").val(),tr_id:tr_id,td_id:td_id},
        type: 'post',
        success: function (res) {
            console.log(res);
        },

        
    });
});

$(document).on("click",".add-row", function () {
    tr = $(this).parent(); 
     tr_id = tr.find("div").data('tr_id');
    td_id = tr.find("div").data('td_id');
   tr.find("div").text(tr.find("input").val());
   
       $.ajax({
        url: '/admin/table-cells/add-row',
        data: {attr: 'value',value: tr.find("input").val(),tr_id:tr_id,td_id:td_id},
        type: 'post',
        success: function (res) {
            console.log(res);
        },

        
    });
});

$(document).on("click",".add-row", function () {
    tr = $(this).parent(); 
     tr_id = tr.find("div").data('tr_id');
    td_id = tr.find("div").data('td_id');
   tr.find("div").text(tr.find("input").val());
   
       $.ajax({
        url: '/admin/table-cells/add-row',
        data: {attr: 'value',value: tr.find("input").val(),tr_id:tr_id,td_id:td_id},
        type: 'post',
        success: function (res) {
            console.log(res);
        },

        
    });
});



JS;


Yii::$app->view->registerJs($js,\yii\web\View::POS_READY);





<?php

use common\models\TableCells;
use \backend\utils\D;
use yii\helpers\Html;


$table_id = 1;
$query_row = TableCells::find()->where(['table_id' => $table_id])->select('tr_id');
$rows = $query_row->distinct()->asArray()->orderBy('tr_id')->all();
echo Html::button(" ADD ROW", ['id' => 'add-row-button', 'class' => 'btn btn-success']);
echo Html::button(" ADD COLUMN", ['id' => 'add-column-button', 'class' => 'btn btn-success']);
\yii\widgets\Pjax::begin(['id' => 'pjax-table']);
echo Html::tag("h3", "ТАБЛИЦА");
if ($rows) {
    $trs = '';
    foreach ($rows as $keyRow => $row) {

        $row_sells_query = TableCells::find()->where(['table_id' => $table_id])->orderBy('td_id')->andWhere(['tr_id' => $row['tr_id']]);
        if ($cells = $row_sells_query->asArray()->all()) {
            $tds = '';
            foreach ($cells as $cell) {
                if ($keyRow == 0) $tds_head .= Html::tag('td', $this->render("_action_buttons_column", ['td_id' => $cell['td_id'],'max_column' => count($cells)]));

                $value = Html::tag('div', $cell['value'], [
                        // 'class' => 'edit-cell',
                        'id' => "cell_id_" . $cell['tr_id'] . "_" . $cell['td_id'],

                        'title' => 'Комментарий',
                        // 'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                    ])
                    . Html::input('text', '', $cell['value'],
                        [
                            'class' => 'edit-title-input hidden',
                            'id' => "input_id_" . $cell['tr_id'] . "_" . $cell['td_id'],

                        ]);
                $tds .= Html::tag('td', $value,
                    [
                        'class' => 'edit-cell',
                        'data' => [
                            'tr_id' => $cell['tr_id'],
                            'td_id' => $cell['td_id']
                        ]

                    ]);
            }
        }

        if ($keyRow == 0) $trs_head .= Html::tag('tr', $tds_head);

        $trs .= Html::tag('tr', $tds . Html::tag('td', $this->render("_action_buttons", ['tr_id' => $cell['tr_id'], 'max_row' => count($rows)])));
    }
    $TableHead = Html::tag('thead',$trs_head);
    $table = Html::tag("table", $TableHead.$trs, ['class' => 'table table-stripped table-bordered']);
}
echo $table;
\yii\widgets\Pjax::end();

?>


<?php

$js = <<<JS
window.table_id = $table_id;
$(document).on('click', function(e) {
    if ( e.target.tagName == 'INPUT' ) {
        if ((window.old_clicked_tr_id != window.clicked_tr_id) && (window.old_clicked_td_id != window.clicked_td_id)) {
            save_element();
       }
      
    }  else save_element();
});

function save_element() {
    input = document.getElementById("input_id_" + window.old_clicked_tr_id + "_" +window.old_clicked_td_id);
    if (input)  {
        value = input.value;
        document.getElementById("cell_id_" + window.old_clicked_tr_id + "_" +window.old_clicked_td_id).innerText = value;
        input.classList.toggle('hidden');
        document.getElementById("cell_id_" + window.old_clicked_tr_id + "_" +window.old_clicked_td_id).classList.toggle('hidden');
          console.log("UPDATE VALUE  = " + value + " TR_ID = " + window.old_clicked_tr_id  + " TD_ID = " + window.old_clicked_td_id );
     $.ajax({
        url: '/admin/table-cells/change',
        data: {attr: 'value',value: value,tr_id:window.old_clicked_tr_id,td_id:window.old_clicked_td_id},
        type: 'post',
        success: function (res) {
            console.log(res);
        },

        
    });
          
    }
         
          
}

$(document).on('click','#add-row-button', function() {
   // table_id = $(this).data('table_id');
     $.ajax({
        url: '/admin/table-cells/add-row',
        data: {table_id: window.table_id},
        type: 'get',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});


$(document).on('click','#add-column-button', function() {
   // table_id = $(this).data('table_id');
     $.ajax({
        url: '/admin/table-cells/add-column',
        data: {table_id: window.table_id},
        type: 'get',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
        },

        
    });
});



$(document).on('click','.delete-row-button', function() {
    delete_tr_id = $(this).data('tr_id');
     $.ajax({
        url: '/admin/table-cells/delete-row',
        data: {tr_id: delete_tr_id,table_id: window.table_id},
        type: 'get',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on('click','.delete-column-button', function() {
    delete_td_id = $(this).data('td_id');
     $.ajax({
        url: '/admin/table-cells/delete-column',
        data: {td_id: delete_td_id,table_id: window.table_id},
        type: 'get',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on('click','.row-priority-change', function() {
    change_tr_id = $(this).data('tr_id');
    priority = $(this).data('priority');
     $.ajax({
        url: '/admin/table-cells/change-priority',
        data: {tr_id: change_tr_id,table_id: window.table_id,priority:priority},
        type: 'post',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on('click','.column-priority-change', function() {
    change_td_id = $(this).data('td_id');
    priority = $(this).data('priority');
     $.ajax({
        url: '/admin/table-cells/change-priority-column',
        data: {td_id: change_td_id,table_id: window.table_id,priority:priority},
        type: 'post',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on("click",".edit-cell", function () {
    window.old_clicked_tr_id = window.clicked_tr_id;
    window.old_clicked_td_id = window.clicked_td_id;
    
    window.clicked_tr_id = $(this).data('tr_id');
    window.clicked_td_id = $(this).data('td_id');
  //  console.log("CELL " + window.clicked_td_id);
   // console.log("ROW " + window.clicked_tr_id);
    $(this).find("input").removeClass('hidden');
    $(this).find("div").addClass('hidden');
   
});



JS;


Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);





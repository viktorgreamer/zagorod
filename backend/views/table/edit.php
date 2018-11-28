<?php

use common\models\TableCells;
use \backend\utils\D;
use yii\helpers\Html;
use yii\bootstrap\Collapse;

use \common\models\Icons;


/* @var $smeta \common\models\Smeta */
if ($smeta = \common\models\Smeta::find()->where(['forTest' => 1])->one()) {
    if ($variables = $smeta->loadVariables()) {
        foreach ($variables as $key => $variable) {
            $body .= " <br>" . $value . " " . $key . " => " . $variable;
        }

    };

};


$table_id = 1;
$table = \common\models\Table::findOne($table_id);
$estimate = \common\models\Estimate::findOne($table->estimate_id);
$inputs = $estimate->inputs;
$query_row = TableCells::find()->where(['table_id' => $table_id])->select('tr_id');

$rows = $query_row->distinct()->asArray()->orderBy('tr_id')->all();
echo Html::button(" ADD ROW", ['id' => 'add-row-button', 'class' => 'btn btn-success']);
echo Html::button(" ADD COLUMN", ['id' => 'add-column-button', 'class' => 'btn btn-success']);
echo Html::button(" Объеденить", ['id' => 'combine', 'class' => 'btn btn-primary']);
echo Html::button(" H1", ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::H1]]);
echo Html::button(" H4", ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::H4]]);
echo Html::button(Icons::ALIGH_LEFT, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::LEFT]]);
echo Html::button(Icons::ALIGH_CENTER, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::CENTER]]);
echo Html::button(Icons::ALIGH_RIGHT, ['class' => 'btn btn-primary format', 'data' => ['format_type' => TableCells::RIGHT]]);
\yii\widgets\Pjax::begin(['id' => 'pjax-table']);
echo Html::tag("h3", "ТАБЛИЦА");
if ($rows) {
    $trs = '';
    foreach ($rows as $keyRow => $row) {
        $table_row = \common\models\TableRows::find()->where(['table_id' => $table_id])->andWhere(['tr_id' => $row['tr_id']])->one();

        $row_sells_query = TableCells::find()->where(['table_id' => $table_id])->orderBy('td_id')->andWhere(['tr_id' => $row['tr_id']]);

        if ($cells = $row_sells_query->asArray()->all()) {
            $tds = '';
            foreach ($cells as $cell) {

                $comment_value = $smeta->ReplaceValue($cell['value']);


                $value = Html::tag('div', $cell['value'], [
                        // 'class' => 'edit-cell',
                        'id' => "cell_id_" . $cell['tr_id'] . "_" . $cell['td_id'],

                        'title' => $comment_value,
                        // 'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                    ])
                    . Html::input('text', '', $cell['value'],
                        [
                            'class' => 'edit-title-input hidden',
                            'id' => "input_id_" . $cell['tr_id'] . "_" . $cell['td_id'],

                        ]);

                if ($cell['align']) $alignClass = " ".$cell['align'];

                $tds .= Html::tag('td', $value,
                    [
                        'colspan' => $cell['colspan'],
                        'class' => 'edit-cell active_cell'.$alignClass,
                        'id' => 'cell_' . $cell['tr_id'] . "_" . $cell['td_id'],
                        'data' => [
                            'tr_id' => $cell['tr_id'],
                            'td_id' => $cell['td_id']
                        ],

                    ]);
            }
            $row_class = 'success';
            if ($table_row->result) {
                $result_value = $smeta->ReplaceValue($table_row->result);
                if ($result_value === $table_row->result) {
                    $table_row->result = "<text style=\"color:red\">" . $table_row->result . "</text>";
                    $row_class = 'danger';
                } elseif (!$result_value) {
                    $row_class = 'warning';
                }
            }


            $value = Html::tag('div', $table_row->result, [
                    // 'class' => 'edit-cell',
                    'id' => "cell_id_" . $cell['tr_id'] . "_0",

                    'title' => $result_value,
                    // 'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                ])
                . Html::input('text', 'result_row_' . $cell['tr_id'], $table_row->result,
                    [
                        'class' => 'edit-title-input hidden',
                        'id' => "input_id_" . $cell['tr_id'] . "_0",

                    ]);
            $tds .= Html::tag('td', $value,
                [

                    'class' => 'edit-cell',
                    'data' => [
                        'tr_id' => $cell['tr_id'],
                        'td_id' => '0'
                    ],

                ]);


        }


        $td_action = Html::tag('td', $this->render("_action_buttons", ['tr_id' => $cell['tr_id'], 'max_row' => count($rows)]));
        $trs .= Html::tag('tr', $tds . $td_action, ['class' => $row_class]);
    }

    if ($tableColumns = \common\models\TableColumns::find()->Where(['table_id' => $table_id])->asArray()->all()) {
      //  D::dump($tableColumns);
        foreach ($tableColumns as $column) {
            $options = [];
            if ($width = $column['width']) {
               $options = ['width' => $width.'px'];
            };
            $tds_head .= Html::tag('td', $this->render("_action_buttons_column", ['td_id' => $column['td_id'], 'max_column' => count($tableColumns)]),$options);

    }

    }

    $trs_head .= Html::tag('tr', $tds_head . "<td>Условие</td>");


    $TableHead = Html::tag('thead', $trs_head);
    $table = Html::tag("table", $TableHead . $trs, ['class' => 'table table-stripped table-bordered report-table','id' => 'report_table']);
}
echo $table;
\yii\widgets\Pjax::end();
?>

    <textarea class="form-control" rows="5" id="variables"><?php $table->variables; ?></textarea>
  <?= Html::button('Сохранить переменные',['id' => 'save-variables','class' => 'btn btn-success']); ?>



<?php

echo Collapse::widget([
    'items' => [
        // equivalent to the above
        [
            'label' => 'Переменные',
            'content' => $body,

        ], [
            'label' => 'Ссылки на переменный',
            'content' => $this->render("/output/_data", compact('inputs')),
        ],

    ]
]);
?>


<?php

$js = <<<JS
window.table_id = $table_id;
$(document).on('click', function(e) {
    if ( e.target.tagName == 'INPUT' ) {
        if ((window.old_clicked_tr_id != window.clicked_tr_id) && (window.old_clicked_td_id != window.clicked_td_id)) {
            save_element();
            window.old_clicked_td_id = 0;
            window.old_clicked_tr_id = 0;
       }
      
    }  else save_element();
});

$(document).on('click','#combine', function() {
  
  if (!window.combineStatus) {
      window.combineStatus = true;
      toastr.success("Объединения активировано");
  } else {
      window.combineStatus = false;
      toastr.error("Выделение деактивировано");
  }
});

$(document).on('click','.format', function() {
  
  window.formatStatus = true;
      window.format_type = $(this).data('format_type');
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
        data: {attr: 'value',value: value,tr_id:window.old_clicked_tr_id,td_id:window.old_clicked_td_id,table_id: window.table_id},
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

$(document).on('click','#save-variables', function() {
    variables = $('#variables').val();
     $.ajax({
        url: '/admin/table/save-variables',
        data: {table_id: window.table_id,variables: variables},
        type: 'post',
        success: function (res) {
            console.log(res);
            
             
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
$(document).on('click','.column-width-change', function() {
    change_td_id = $(this).data('td_id');
    width = $(this).data('width');
     $.ajax({
        url: '/admin/table-cells/change-width',
        data: {td_id: change_td_id,table_id: window.table_id,width:width},
        type: 'post',
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
    if (window.combineStatus === true) {
        if (window.first_tr_id && window.first_td_id) {
            window.second_tr_id = $(this).data('tr_id');
            window.second_td_id = $(this).data('td_id');
            console.log(" ПРОВОДИМ ОБЪЕДИНЕНИЕ ЯЧЕЕК");
                window.combineStatus = false;
                if (window.first_td_id < window.second_td_id) {
                    td_to_delete = window.second_td_id;
                    td_col_span = window.first_td_id;
                    } else {
                    td_to_delete = window.first_td_id;
                    td_col_span = window.second_td_id
                    }
                    
                    colspan_colspan = $('#cell_' + window.first_tr_id + '_' + td_col_span).attr('colspan');
                if (!colspan_colspan) colspan_colspan = 1;
                    colspan_to_delete = $('#cell_' + window.first_tr_id + '_' + td_to_delete).attr('colspan');
                    if (!colspan_to_delete) colspan_to_delete = 1;
                    colspan  = parseInt(colspan_colspan) + parseInt(colspan_to_delete);
                    console.log('colspan' + colspan);
                    $('#cell_' + window.first_tr_id + '_' + td_to_delete).remove();
                    $('#cell_' + window.first_tr_id + '_' + td_col_span).attr('colspan',colspan);
                    $('#cell_' + window.first_tr_id + '_' + td_col_span).attr('style','');
                    
                     $.ajax({
        url: '/admin/table-cells/combine',
        data: {tr_id: window.first_tr_id ,td_to_delete: td_to_delete,td_col_span: td_col_span ,table_id: window.table_id},
        type: 'post',
        success: function (res) {
            console.log(res);
        },

        
    });
                     
                 
                toastr.error("Выделение деактивировано");
            window.first_tr_id = 0;
            window.first_td_id = 0;
            window.second_tr_id = 0;
            window.second_td_id = 0;
       
            
        } else {
        window.first_tr_id = $(this).data('tr_id');
        window.first_td_id = $(this).data('td_id');
        $(this).attr('style','background:grey')
            
        }
        
        
    } else if (window.formatStatus === true) {
         $.ajax({
        url: '/admin/table-cells/format',
        data: {tr_id: $(this).data('tr_id') ,td_id: $(this).data('td_id'),format: window.format_type,table_id: window.table_id},
        type: 'post',
        success: function (res) {
            console.log(res);
             $.pjax.reload('#pjax-table',{timeout : false});
        }
         });
         
          window.formatStatus = false;
        
    } else {
         window.old_clicked_tr_id = window.clicked_tr_id;
        window.old_clicked_td_id = window.clicked_td_id;
    
    window.clicked_tr_id = $(this).data('tr_id');
    window.clicked_td_id = $(this).data('td_id');
  //  console.log("CELL " + window.clicked_td_id);
   // console.log("ROW " + window.clicked_tr_id);
    $(this).find("input").removeClass('hidden');
    $(this).find("div").addClass('hidden');
    }
   
   
});



JS;


Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);





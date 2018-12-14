<?php

use common\models\TableCells;
use \backend\utils\D;
use yii\helpers\Html;
use yii\bootstrap\Collapse;

use \common\models\Icons;


/* @var $smeta \common\models\Smeta */
if ($smeta = \common\models\Smeta::find()->where(['forTest' => 1])->one()) {
    if ($variables = $smeta->loadVariables()) {

    };

};


$table = \common\models\Table::findOne($table_id);
$estimate = \common\models\Estimate::findOne($table->estimate_id);
$inputs = $estimate->inputsAll;
$events = $estimate->events;
$outputs = $estimate->outputs;
$query_row = TableCells::find()->where(['table_id' => $table_id])->select('tr_id');

$rows = $query_row->distinct()->asArray()->orderBy('tr_id')->all();
echo $this->render('_format_panel', compact('table'));

\yii\widgets\Pjax::begin(['id' => 'pjax-table']);
echo Html::tag("h3", "ТАБЛИЦА");
if ($rows) {

    /*  if ($_GET['show_result']) echo "SHOW_RESULTS";
      else echo "DEBUG PANEL";*/
    $trs = '';
    foreach ($rows as $keyRow => $row) {
        $table_row = \common\models\TableRows::find()->where(['table_id' => $table_id])->andWhere(['tr_id' => $row['tr_id']])->one();

        $row_sells_query = TableCells::find()->where(['table_id' => $table_id])->orderBy('td_id')->andWhere(['tr_id' => $row['tr_id']]);

        if ($cells = $row_sells_query->asArray()->all()) {
            $tds = '';
            foreach ($cells as $cell) {
// вычисляем актична ли строка
                $row_class = 'success';
                $cell_value = '';
                $title = '';
                if ($table_row->result) {
                    $result_value = $smeta->ReplaceValue($table_row->result);
                    if ($result_value === $table_row->result) {

                        // $table_row->result = "<text style=\"color:red\">" . $table_row->result . "</text>";
                        $row_class = 'danger';
                    } else {
                        $value = \common\models\Evaluator::makeBoolean($result_value);

                        if (!$value['value']) {
                            $row_class = 'warning';
                        }
                        if ($_GET['show_result']) {
                            $title = $table_row->result;
                            $cell_value = $value['value'];
                        } else {
                            $cell_value = $table_row->result;
                            $title = $value['value'];
                        }

                    }
                }

                if (($_GET['show_result'])) {

                    $comment_value = $cell['value'];
                    $response = \common\models\Evaluator::make($smeta->ReplaceValue($cell['value']), $cell['type']);
                    $contentClass = $response['type'];
                    $value = $response['value'];
                } else {
                    $response = \common\models\Evaluator::make($smeta->ReplaceValue($cell['value']), $cell['type']);
                    $contentClass = $response['type'];
                    $comment_value = $response['value'];
                    $value = $cell['value'];
                }

                if ($row_class !== 'success') $smeta->addVariables([$cell['address'] => 0]); else $smeta->addVariables([$cell['address'] => $response['value']]);
                $value = Html::tag('cell', $value, [
                        'class' => 'edit-cell ' . $contentClass,
                        'id' => "cell_id_" . $cell['tr_id'] . "_" . $cell['td_id'],
                        'title' => $comment_value,
                        // 'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                    ])
                    . Html::input('text', '', $cell['value'],
                        [
                            'class' => 'edit-title-input hidden',
                            'id' => "input_id_" . $cell['tr_id'] . "_" . $cell['td_id'],

                        ]);

                if ($cell['align']) $alignClass = " " . $cell['align'];
                if ($cell['fillColor']) $style = 'background-color: #' . $cell['fillColor']; else $style = '';

                $tds .= Html::tag('td', $value,
                    [
                        'colspan' => $cell['colspan'],
                        'class' => "edit-cell active_cell " . $contentClass . " " . $cell['classes'],
                        'id' => 'cell_' . $cell['tr_id'] . "_" . $cell['td_id'],
                        'data' => [
                            'tr_id' => $cell['tr_id'],
                            'td_id' => $cell['td_id'],
                            'address' => $cell['address']
                        ],
                        'style' => $style

                    ]);
            }


            $value = Html::tag('cell', $cell_value, [
                    'class' => 'div-cell',
                    'id' => "cell_id_" . $cell['tr_id'] . "_0",

                    'title' => $title,
                    // 'data' => ['tr_id' => $cell['tr_id'],'td_id' => $cell['td_id']]]
                ])
                . Html::input('text', 'result_row_' . $cell['tr_id'], $table_row->result,
                    [
                        'class' => 'edit-title-input hidden',
                        'id' => "input_id_" . $cell['tr_id'] . "_0",

                    ]);
            $tds .= Html::tag('td', $value,
                [

                    'class' => 'edit-cell  text-center',
                    'data' => [
                        'tr_id' => $cell['tr_id'],
                        'td_id' => '0'
                    ],

                ]);


        }

        $td_pre = Html::tag('td', $this->render("_action_buttons", ['tr_id' => $cell['tr_id'], 'max_row' => count($rows)]), ['class' => 'text-center']);
        $trs .= Html::tag('tr', $td_pre . $tds, ['class' => $row_class]);
    }

    if ($tableColumns = \common\models\TableColumns::find()->Where(['table_id' => $table_id])->asArray()->all()) {
        //  D::dump($tableColumns);
        foreach ($tableColumns as $column) {
            $options = [];
            if ($width = $column['width']) {
                $options = ['style' => "width:" . $width . "px"];
            };
            $tds_head .= Html::tag('td', $this->render("_action_buttons_column", ['td_id' => $column['td_id'], 'column' => $column, 'max_column' => count($tableColumns), 'width' => $width, 'column_address' => TableCells::$letters[$column['td_id'] - 1]]), $options);

        }

    }

    $trs_head .= Html::tag('tr', "<td style='width: 40px'></td>" . $tds_head . "<td>Условие</td>");


    $TableHead = Html::tag('thead', $trs_head);
    $table_html = Html::tag("table", $TableHead . $trs, ['class' => 'table table-stripped table-bordered report-table', 'width' => '100%', 'id' => 'report_table']);
}
echo $table_html;
\yii\widgets\Pjax::end();
?>

    <textarea class="form-control" rows="5" id="variables"><?php echo $table->variables; ?></textarea>
<?= Html::button('Сохранить переменные', ['id' => 'save-variables', 'class' => 'btn btn-success']); ?>
    <textarea id="clipboard_area" hidden></textarea>
<?php
if ($variables = $smeta->getVariables()) {
    foreach ($variables as $key => $variable) {
        $body .= " <br>" . $key . " => " . $variable;
    }

};

echo Collapse::widget([
    'items' => [
        // equivalent to the above
        [
            'label' => 'Переменные',
            'content' => $body,

        ], [
            'label' => 'Ссылки на переменные',
            'content' => $this->render("/output/_data", compact(['inputs', 'outputs', 'events'])),
        ],
        [
            'label' => 'Справочник функций',
            'content' => $this->render("/output/functions/_functions"),
        ],

    ]
]);
?>


<?php

$js = <<<JS
window.selectedCells = [];
window.dblclickedCells = [];
window.IsActiveCell = false;
window.table_id = $table_id;

$(document).on('keypress',function(e) {
    if(e.which == 13) {
    if (window.IsActiveCell) {
         save_element();
       window.IsActiveCell = false;
    }
    console.log(' KEYPRESSED '+ window.IsActiveCell);
    }
  
    
});

$(document).on('click','#combine', function() {
   window.editStatusChanged = true;
   window.formatStatus = false; 
  if (!window.combineStatus) {
      window.combineStatus = true;
      toastr.success("Объединения активировано");
  } else {
      window.combineStatus = false;
     
      toastr.error("Выделение деактивировано");
  }
});

$(document).on('click','button.format', function() {
    
      if (window.combineStatus === true) {
          window.combineStatus = false;
            toastr.error("Выделение деактивировано");
            window.first_tr_id = 0;
            window.first_td_id = 0;
            window.second_tr_id = 0;
            window.second_td_id = 0;
      }
      
       if (window.format_type == $(this).data('format_type'))  window.formatStatus = false; 
         else window.formatStatus = true;
        window.format_type = $(this).data('format_type'); 
        
      selectedCells = $(document).find("td.selected");
     if (selectedCells) {
         addresses = [];
          selectedCells.each( function(index,element) {
           address =  $(this).data('address');
           addresses.push(address);
          
          });
           $.ajax({
        url: '/admin/table-cells/multi-format',
        data: {addresses: addresses ,format: window.format_type,table_id: window.table_id},
        type: 'post',
        success: function (res) {
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        }
         });
             $('button.active').removeClass('active');
           
           console.log(addresses + " SELECTED");
     } else {
         isActive = $(this).hasClass('active');
        $('button.active').removeClass('active');
        if (!isActive) $(this).addClass('active');
        
     }

});

$(document).on('click','.select-color', function() {
    console.log(" SELECT COLOR WAS CLICKED");
      if (window.combineStatus === true) {
          window.combineStatus = false;
            toastr.error("Выделение деактивировано");
            window.first_tr_id = 0;
            window.first_td_id = 0;
            window.second_tr_id = 0;
            window.second_td_id = 0;
      }
      
       if (window.color == $(this).data('color'))  window.formatStatus = false; 
         else window.formatStatus = true;
        window.color = $(this).data('color'); 
        
      selectedCells = $(document).find("td.selected");
     if (selectedCells) {
         addresses = [];
          selectedCells.each( function(index,element) {
           address =  $(this).data('address');
           addresses.push(address);
          
          });
           $.ajax({
        url: '/admin/table-cells/multi-color',
        data: {addresses: addresses ,color: window.color,table_id: window.table_id},
        type: 'post',
        success: function (res) {
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        }
         });
             $('button.active').removeClass('active');
           
           console.log(addresses + " SELECTED");
     } else {
         isActive = $(this).hasClass('active');
        $('button.active').removeClass('active');
        if (!isActive) $(this).addClass('active');
        
     }

});

$(document).on('click','button.render_sum', function() {
    selectedCells = $(document).find("td.selected");
     if (selectedCells) {
         addresses = [];
          selectedCells.each( function(index,element) {
           address =  $(this).data('address');
           addresses.push(address);
         
          
          });
     }
       render_sum = addresses.join("+");
    // toastr.success("Выделено ",render_sum);
     copyToClipboard(render_sum);
          

});

$(document).on('click','button.set_type', function() {
    settedType = $(this).data('type');
    selectedCells = $(document).find("td.selected");
     if (selectedCells) {
         addresses = [];
          selectedCells.each( function(index,element) {
           address =  $(this).data('address');
           addresses.push(address);
         
          
          });
          $.ajax({
        url: '/admin/table-cells/multi-set-type',
        data: {addresses: addresses ,type: settedType,table_id: window.table_id},
        type: 'post',
        success: function (res) {
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        }
         });
     }
      
         

});

function save_element() {
    input = document.getElementById("input_id_" + window.clicked_tr_id + "_" +window.clicked_td_id);
    if (input)  {
        value = input.value;
        document.getElementById("cell_id_" + window.clicked_tr_id + "_" +window.clicked_td_id).innerText = value;
        input.classList.toggle('hidden');
        document.getElementById("cell_id_" + window.clicked_tr_id + "_" +window.clicked_td_id).classList.toggle('hidden');
          console.log("UPDATE VALUE  = " + value + " TR_ID = " + window.old_clicked_tr_id  + " TD_ID = " + window.old_clicked_td_id );
     $.ajax({
        url: '/admin/table-cells/change',
        data: {attr: 'value',value: value,tr_id:window.clicked_tr_id,td_id:window.clicked_td_id,table_id: window.table_id},
        type: 'post',
        success: function (res) {
            res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
          
        },

        
    });
          
    } else {
        console.log(" ELEMENTS TO SAVE NOT FOUND");
    }
           
         
          
}

function update_history_list(table_history) {
      li = "<li><a class='choose_history' data-table_history_id = '" + table_history.id + "' title = '" + table_history.time + "' >" + table_history.name + "</a></li>";
      if (history_list = $('#history-list')) {
          history_list.prepend(li);
           $('#history-list li').last().remove();
      }
             
}


$(document).on('click','.choose_history', function() {
  history_id = $(this).data('table_history_id');
  console.log(" HISTORY ID WAS CLICKED " + history_id);
  
  $.ajax({
        url: '/admin/table/restore-history',
        data: {history_id: history_id},
        type: 'post',
        success: function (res) {
            console.log(res.status);
           $.pjax.reload('#pjax-table',{timeout : false});
        },

        
    });
          
  
});

$(document).on('click','#add-row-button', function() {
   // table_id = $(this).data('table_id');
     $.ajax({
        url: '/admin/table-cells/add-row',
        data: {table_id: window.table_id},
        type: 'get',
        success: function (res) {
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on('click','.toggle-column-visibility', function() {
    td_id = $(this).data('td_id');
     $.ajax({
        url: '/admin/table-cells/toggle-visibility-column',
        data: {table_id: window.table_id,td_id:td_id},
        type: 'post',
        success: function (res) {
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
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
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        },

        
    });
});

$(document).on('click','.copy-row-from-row', function() {
    tr_id = $(this).data('tr_id');
     $.ajax({
        url: '/admin/table-cells/copy-row',
        data: {table_id: window.table_id,tr_id:tr_id},
        type: 'get',
        success: function (res) {
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        },

        
    });
});

$(document).on('click','.select-row', function() {
    tr_id = $(this).data('tr_id');
    console.log(tr_id + ' SELECTED');
    cells = $('td[data-tr_id="' + tr_id + '"].active_cell');
   
   cells.addClass('selected');
});

$(document).on('click','.select-column', function() {
    td_id = $(this).data('td_id');
    console.log(td_id + ' SELECTED');
    cells = $('td[data-td_id="' + td_id + '"].active_cell');
   
   cells.addClass('selected');
});

$(document).on('click','.delete-row-button', function() {
    if (confirm('Удалить?')) {
         delete_tr_id = $(this).data('tr_id');
     $.ajax({
        url: '/admin/table-cells/delete-row',
        data: {tr_id: delete_tr_id,table_id: window.table_id},
        type: 'get',
        success: function (res) {
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
    }
   
});

$(document).on('click','.delete-column-button', function() {
console.log(" delete-column-button CLICKED");
if (confirm('Удалить?')) {
    delete_td_id = $(this).data('td_id');
     $.ajax({
        url: '/admin/table-cells/delete-column',
        data: {td_id: delete_td_id,table_id: window.table_id},
        type: 'get',
        success: function (res) {
            res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
            
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
     }
});

$(document).on('click','.column-width-change', function() {
    change_td_id = $(this).data('td_id');
    width = $(this).data('width');
     $.ajax({
        url: '/admin/table-cells/change-width',
        data: {td_id: change_td_id,table_id: window.table_id,width:width},
        type: 'post',
        success: function (res) {
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
            
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
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
            
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
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
            
             $.pjax.reload('#pjax-table',{timeout : false});
             
        },

        
    });
});

$(document).on("click","td.edit-cell", function () {
    $(this).toggleClass('selected');
    console.log('ONE CLICK ADDED');
    
     if (window.combineStatus === true) {
        if (window.first_tr_id && window.first_td_id) {
             window.editStatusChanged = false;
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
               res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
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
        }
        
        
    } 
   /* if (window.formatStatus === true) {
         
         $.ajax({
        url: '/admin/table-cells/format',
        data: {tr_id: $(this).data('tr_id') ,td_id: $(this).data('td_id'),format: window.format_type,table_id: window.table_id},
        type: 'post',
        success: function (res) {
              res = JSON.parse(res);
            console.log(res.history);
            update_history_list(res.history);
             $.pjax.reload('#pjax-table',{timeout : false});
        }
         });
         
        
    }   */
    
});

$(document).on("dblclick","td.edit-cell", function () {
     console.log('DUBBLE CLICK ADDED');
     
        window.clicked_tr_id = $(this).data('tr_id');
    window.clicked_td_id = $(this).data('td_id');
    console.log("CELL " + window.clicked_td_id);
    console.log("ROW " + window.clicked_tr_id); 
    
    $(this).find("input").removeClass('hidden');
    $(this).find("cell").addClass('hidden');
    window.IsActiveCell = true;
    
    ;
    
   
   
});



JS;


Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);





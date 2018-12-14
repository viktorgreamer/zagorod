<?php

use unclead\multipleinput\MultipleInput;


echo MultipleInput::widget(
    [
        'name' => "name",
        'max' => 4,
        'columns' => [
            [
                'name' => 'name',
                'title' => 'Name',
                'options' => [
                    'class' => 'input-priority'
                ]
            ],
            [
                'name' => 'rule',
                'type' => 'dropDownList',
                'title' => 'Правило Валидации',
                'defaultValue' => 0,
                'items' => [1 => 1,2 => 2,3 => 3, 4=> 4, 5 => 5],
                'options' => ['data' => ['is_relative' => 1]]

            ],
            [
                'name' => 'type',
                'type' => 'dropDownList',
                'title' => "Тип",
                'defaultValue' => 0,
                'items' => ['' => 'Текстовое поле', 'dropDownList' => "Выпадающий список"]
            ],

        ],
        'options' =>
            ['data' => ['is_relative' => 1]]
    ]);

$script = <<< JS
let selected_values = [];
jQuery('.multiple-input').on('beforeAddRow', function(e) {
      multipleSelectedOptions = $(this).find('select[data-is_relative="1"]');
    
         multipleSelectedOptions.each( function() {
         //  console.log($(this).attr('name'));
        selected_values.push($(this).val());
    });
    selected_values  = selected_values.filter((v, i, a) => a.indexOf(v) === i);
    console.log(selected_values);
    
      
   // console.log(multipleSelectedOptions);
   // console.log('calls on before add row event');
}).on('afterAddRow', function(e, row) {
    multipleSelectedOptions = $(this).find('select[data-is_relative="1"]');
    multipleSelectedOptions.each( function() {
             $(this).find('option').each( function() {
                 value = $(this).attr('value');
                 isSelected = $(this).is(':selected');
                   //  console.log(isSelected);
               if (selected_values.indexOf(value) != -1) {
                  if (isSelected === 1) {
                         console.log("value " + value + " in array " + selected_values + " AND " + isSelected + ' ... skiping');
                         
                  } else  {
                         console.log("value " + value + " in array " + selected_values + " AND " + isSelected + ' ... removing');
                
                  $(this).remove(); 
                  }
               } else console.log("value " + value + " NOT IN in array " + selected_values + " AND " + isSelected + ' ... skiping');
               
             });
                    
           
       
      });
   // console.log('calls on after add row event');
}).on('beforeDeleteRow', function(e, row){
  
    // row - HTML container of the current row for removal.
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('Are you sure you want to delete row?')
}).on('afterDeleteRow', function(e, row){
    console.log('calls on after remove row event');
    console.log(row);
}).on('afterDropRow', function(e, item){       
    console.log('calls on after drop row', item);
});

JS;
$this->registerJs($script, yii\web\View::POS_READY);
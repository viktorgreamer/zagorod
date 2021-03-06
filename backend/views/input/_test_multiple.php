<?php

use unclead\multipleinput\MultipleInput;

$items = [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'];
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
                'items' => $items,
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

$items = json_encode(array_values($items));

$script = <<< JS
items_name_rules = $items;
console.log(items_name_rules);
function wait(ms){
    start = new Date().getTime();
    end = start;
   while(end < start + ms) {
     end = new Date().getTime();
  }
}

let selected_values = [];
window.elementsToRemoveGlobal = [];
window.elementsToRemove = [];
window.options_selected = [];

$(document).on('change', '.multiple-input select[data-is_relative="1"]', function(e) { 
     console.log("SELECT OPTION WAS CHANGED ");
    availableOptions = items_name_rules.slice();
    elementsToRemoveGlobal = [];
    elementsToRemove = [];
    options_selected_before = [];

    multipleSelectedOptions = jQuery('.multiple-input').find('select[data-is_relative="1"]');
  //  countOptionSelectedBefore = multipleSelectedOptions.length;
 
    multipleSelectedOptions.each( function(i) {
       // console.log(" INDEX = " + i + " " + $(this).attr('name'));
        options_selected_before.push($(this).val());
        value = $(this).val();
        indexOfElementToDelete = availableOptions.indexOf(value);
        if (indexOfElementToDelete !== -1) availableOptions.splice(indexOfElementToDelete, 1);
    });
    
     console.log("AVAILABLE OPTIONS " + availableOptions);
    
    multipleSelectedOptions.each( function(i) {
         console.log(" ITERATING OBJECT NAME = " + $(this).attr('name'));
             $(this).find('option').each( function() {
                   isSelected = $(this).is(':selected');
               //  console.log("  ITERATING OPTION value = " + $(this).attr('value') + " SELECTED = " + isSelected);
                 value = $(this).attr('value');
             
                  if (isSelected === false) {
                                          if (availableOptions.indexOf(value) == -1) {
                       //  console.log(" value " + value + " in NOT array " + availableOptions + " AND " + isSelected + ' ... removing');
                $(this).addClass('hidden'); 
                         } else {
                                               $(this).removeClass('hidden'); 
                         }
                  } else  {
                      
                   //   console.log("value " + value + "  IN in array " + selected_values + " AND " + isSelected + ' ... skiping');
                  }
             //  } 
               
             });
               
      });   
});


jQuery('.multiple-input').on('beforeAddRow', function(e) {
    
   // console.log(multipleSelectedOptions);
   // console.log('calls on before add row event');
}).on('afterAddRow', function(e, row) {
    
    availableOptions = items_name_rules.slice();
  
    elementsToRemoveGlobal = [];
    elementsToRemove = [];
    options_selected_before = [];
    
    multipleSelectedOptions = $(this).find('select[data-is_relative="1"]');
  //  countOptionSelectedBefore = multipleSelectedOptions.length;
    
    multipleSelectedOptions.each( function(i) {
       // console.log(" INDEX = " + i + " " + $(this).attr('name'));
        options_selected_before.push($(this).val());
        value = $(this).val();
        indexOfElementToDelete = availableOptions.indexOf(value);
        if (indexOfElementToDelete !== -1) availableOptions.splice(indexOfElementToDelete, 1);
    });
    
    options_selected_before  = options_selected_before.filter((v, i, a) => a.indexOf(v) === i);
    var first_element = availableOptions.shift();
    $(this).find('select[data-is_relative="1"]').last().val(first_element);
    options_selected_before.push(first_element);
    multipleSelectedOptions = $(this).find('select[data-is_relative="1"]');
    
       console.log(' SELECTED VALUES =' + options_selected_before + ' AVAILABLE OPTIONS ' + availableOptions);
 
 
   // console.log("COUNT OF ELEMENTS = " + multipleSelectedOptions.length);
    
    multipleSelectedOptions.each( function(i) {
         console.log(" ITERATING OBJECT NAME = " + $(this).attr('name'));
             $(this).find('option').each( function() {
                   isSelected = $(this).is(':selected');
                 console.log("  ITERATING OPTION value = " + $(this).attr('value') + " SELECTED = " + isSelected);
                 value = $(this).attr('value');
             
                  if (isSelected === false) {
                                          if (availableOptions.indexOf(value) == -1) {
                         console.log(" value " + value + " in NOT array " + availableOptions + " AND " + isSelected + ' ... removing');
                $(this).addClass('hidden'); 
                         }
                  } else  {
                       $(this).removeClass('hidden'); 
                      console.log("value " + value + "  IN in array " + selected_values + " AND " + isSelected + ' ... skiping');
                  }
             //  } 
               
             });
               
      });
    
}).on('beforeDeleteRow', function(e, row){
  
    // row - HTML container of the current row for removal.
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('Are you sure you want to delete row?')
}).on('afterDeleteRow', function(e, row){
    
     availableOptions = items_name_rules.slice();
 
    elementsToRemoveGlobal = [];
    elementsToRemove = [];
    options_selected_before = [];
    
    multipleSelectedOptions = $(this).find('select[data-is_relative="1"]');
  //  countOptionSelectedBefore = multipleSelectedOptions.length;
    
    multipleSelectedOptions.each( function(i) {
       // console.log(" INDEX = " + i + " " + $(this).attr('name'));
        options_selected_before.push($(this).val());
        value = $(this).val();
        indexOfElementToDelete = availableOptions.indexOf(value);
        if (indexOfElementToDelete !== -1) availableOptions.splice(indexOfElementToDelete, 1);
    });
    
     console.log("AVAILABLE OPTIONS " + availableOptions);
    
    multipleSelectedOptions.each( function(i) {
         console.log(" ITERATING OBJECT NAME = " + $(this).attr('name'));
             $(this).find('option').each( function() {
                   isSelected = $(this).is(':selected');
                 console.log("  ITERATING OPTION value = " + $(this).attr('value') + " SELECTED = " + isSelected);
                 value = $(this).attr('value');
             
                  if (isSelected === false) {
                                          if (availableOptions.indexOf(value) == -1) {
                         console.log(" value " + value + " in NOT array " + availableOptions + " AND " + isSelected + ' ... removing');
                $(this).addClass('hidden'); 
                         } else {
                                               $(this).removeClass('hidden'); 
                         }
                  } else  {
                      
                      console.log("value " + value + "  IN in array " + selected_values + " AND " + isSelected + ' ... skiping');
                  }
             //  } 
               
             });
               
      });
    
    
    console.log('calls on after remove row event');
    console.log(row);
}).on('afterDropRow', function(e, item){       
    console.log('calls on after drop row', item);
});

JS;
$this->registerJs($script, yii\web\View::POS_READY);
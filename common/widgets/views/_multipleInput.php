<?php

use unclead\multipleinput\MultipleInput;
$id_div = "div-input-id-".$model->input_id;


echo MultipleInput::widget([
        'data' => $model->getGroupValue($value),
        'max' => $model->max,
        'name' => $model->getFormName(),
        'columns' => $model->getColumnsSchema(),
    ]);
?>

    <?php
$script = '';

if ($itemGroups = $model->relatedItemsGroups()) {
    foreach ($itemGroups as $name => $itemGroup) {
       // echo $selector = "#".$id_div." .multiple-input .list-cell__".$name." select[data-is_relative=\"1\"]";
        $itemGroup = json_encode(array_values($itemGroup));
       $multiple_global_selector =  "#".$id_div. " .multiple-input";
        $script .= <<< JS
avalilableItems$name = $itemGroup;
var td_class_name = '.list-cell__$name';
var select_selector = '$selector';
var multiple_global_selector = '$multiple_global_selector';
console.log(avalilableItems$name);
/*
let selected_values = [];
window.elementsToRemoveGlobal = [];
window.elementsToRemove = [];
window.options_selected = [];*/

$(document).on('change', select_selector, function(e) { 
     console.log("SELECT OPTION WAS CHANGED ");
    availableOptions = avalilableItems$name.slice();
    
    options_selected_before = [];

    multipleSelectedOptions = jQuery(select_selector);
  //  countOptionSelectedBefore = multipleSelectedOptions.length;
 
    multipleSelectedOptions.each( function(i) {
        options_selected_before.push($(this).val());
        value = $(this).val();
        indexOfElementToDelete = availableOptions.indexOf(value);
        if (indexOfElementToDelete !== -1) availableOptions.splice(indexOfElementToDelete, 1);
    });
    
     console.log("AVAILABLE OPTIONS " + availableOptions);
    
    multipleSelectedOptions.each( function() {
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


jQuery(multiple_global_selector).on('afterAddRow', function(e, row) {
    console.log("afterAddRow")
    availableOptions = avalilableItems$name.slice();
    options_selected_before = [];
    
    multipleSelectedOptions = $(select_selector);
    
    console.log("COUNT multipleSelectedOptions " + multipleSelectedOptions.length);
    
    multipleSelectedOptions.each( function() {
        options_selected_before.push($(this).val());
        value = $(this).val();
        indexOfElementToDelete = availableOptions.indexOf(value);
        if (indexOfElementToDelete !== -1) availableOptions.splice(indexOfElementToDelete, 1);
    });
    
    options_selected_before  = options_selected_before.filter((v, i, a) => a.indexOf(v) === i);
    var first_element = availableOptions.shift();
    $(select_selector).last().val(first_element);
    options_selected_before.push(first_element);
   
       console.log(' SELECTED VALUES =' + options_selected_before + ' AVAILABLE OPTIONS ' + availableOptions);
 
 
   // console.log("COUNT OF ELEMENTS = " + multipleSelectedOptions.length);
    
    multipleSelectedOptions.each( function() {
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
                      console.log("value " + value + "  IN in array " + options_selected_before + " AND " + isSelected + ' ... skiping');
                  }
             //  } 
               
             });
               
      });
    
}).on('afterDeleteRow', function(e, row){
     availableOptions = avalilableItems$name.slice();
     options_selected_before = [];
    
     multipleSelectedOptions = $(select_selector);
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
                      
                      console.log("value " + value + "  IN in array " + options_selected_before + " AND " + isSelected + ' ... skiping');
                  }
             //  } 
               
             });
               
      });
    
});

JS;
    }

}


$this->registerJs($script, yii\web\View::POS_READY);
<?php


$js = <<<JS
console.log(" SELECTED CELLS WAS");
selectedCells = [{tr_id:1,td_id:2},{tr_id:1,td_id:1}];
console.log(selectedCells);
var cell = {tr_id:1,td_id:1};
selectedCells.forEach( function(item,index) {
 // if (item.tr_id == )
 console.log(item.tr_id + "  " + item.td_id + "  " + index);
})
JS;

Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);
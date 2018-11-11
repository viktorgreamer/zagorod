<?php
/* @var $trace  \common\models\Tracing */
Yii::$app->session->set('old_route',Yii::$app->requestedRoute);
if ($model_id = $_GET['model_id']) {
    $model = \common\models\Tracing::findOne($model_id);
    echo $this->render("/tracing/_form",['model' =>  $model]);

} elseif  ($parent_id = $_GET['parent_id']) {
    $model = new \common\models\Tracing(['parent_id' => $parent_id]);
    echo $this->render("/tracing/_form",['model' =>  $model]);

} else {
    $model = new \common\models\Tracing();
    echo $this->render("/tracing/_form",['model' =>  $model]);
}

$id = $trace->id;
?>
    <h3>Схема трассировки</h3>
    <div class="row text-right">
        <div class="col-lg-12">
            <?php echo \yii\helpers\Html::a(\common\models\Icons::ADD,'',
                [
                    'class' => 'btn btn-success btn-xs',
                    'id' => 'add-node-to-node',
                    // 'disabled' => true,
                ]) ?>
            <?php echo \yii\helpers\Html::a(\common\models\Icons::EDIT,'',
                [
                    'class' => 'btn btn-success btn-xs',
                    'id' => 'edit-node',
                    'disabled' => true,
                ]) ?>

            <?php echo \yii\helpers\Html::button(\common\models\Icons::REMOVE,
                [
                    'class' => 'btn btn-danger btn-xs',
                    'id' => 'remove-node',
                    'disabled' => true,
                ]) ?>

        </div>
    </div>
    <div class="row">
        <?php if ($traces = $smeta->traces) { ?>
            <?php foreach ($traces as $trace) { ?>
                <div class="col-lg-12">
                    <div id="tree<?= $trace->id; ?>" class="trace-tree-view"></div>
                </div>
                <?php
                $tree = ['text' => $trace->name, 'id' => $trace->id, 'nodes' => $trace->tree()];
                $data = "[" . json_encode($tree) . "]";
                $js = <<<JS
                
                
$('#tree$trace->id').treeview({data: $data});
$('#tree$trace->id').treeview('expandAll', { levels: 10, silent: true });

JS;
                Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);


            } ?>


        <? } ?>

    </div>

<?php
$smeta = Yii::$app->session->get('smeta');
// echo $tree;

$js = <<<JS
selected_node = 0;
route = 'fill-data?smeta_id=$smeta->smeta_id';


$(document).on('click', 'button#remove-node', function (e) {
    console.log("button#remove-node");
     $.ajax({
        url: '/tracing/delete-ajax',
        data: {id: selected_node},
        type: 'get',
        success: function (res) {

        },

       
    });
   
});




// событие на выделение ветки
$(document).find('.trace-tree-view').on('nodeSelected', function(event, data) {
    
    
 console.log(" NODE SELECTED ");
 selected_node = data.id;
 $(document).find('#add-node-to-node').attr('href',route+ '&parent_id=' + selected_node);
 $(document).find('#add-node-to-node').attr('disabled', false);
 $(document).find('#edit-node').attr('disabled', false);
 $(document).find('#edit-node').attr('href',route+ '&model_id=' + selected_node);
 
 if (data.nodes) {
     $(document).find('#remove-node').attr('disabled', true); 
     console.log('NODE HAS NODES');
 } else {
    $(document).find('#remove-node').attr('disabled', false);  
 }

 
 console.log(selected_node);
});


$(document).find('.trace-tree-view').on('nodeUnchecked', function(event, data) {
 console.log(" NODE SELECTED ");
 selected_node = 0;
 $(document).find('#add-node-to-node').attr('href',route);
 $(document).find('#edit-node').attr('href',route);
 $(document).find('#edit-node').attr('disabled', true);
 $(document).find('#remove-node').attr('disabled', true); 

 
 console.log(selected_node);
});

// событие на не выделение ветки
$(document).find('.trace-tree-view').on('nodeUnselected', function(event, data) {
 console.log(" NODE UNSELECTED" );
 selected_node = null;
 console.log(selected_node);
});

JS;

Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);


$js = <<<JS
$(document).on('change',"#tracing-cascade",function() {
    console.log(" TRACING REVISION WAS CHANGED");
  if ($(this).val()) $("#tracing-cascade_with_revision").parent().parent().removeClass('hidden');
});


$(document).on('change',"#tracing-cascade_with_revision",function() {
    console.log(" TRACING REVISION WAS CHANGED");
  if ($(this).val()) $("#tracing-turn").parent().parent().removeClass('hidden');
});

$(document).on('change',"#tracing-turn",function() {
    console.log(" TRACING REVISION WAS CHANGED");
  if ($(this).val() != '0') {
  $("#tracing-floor").parent().parent().removeClass('hidden'); 
  } else {
      $("#tracing-floor").parent().parent().addClass('hidden'); 
  }
});


JS;
Yii::$app->view->registerJs($js, \yii\web\View::POS_READY);




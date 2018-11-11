<?php


use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Input;
use common\models\Icons;

?>
<hr class="no-margin">
<div class="stage-list-view" id="div-stage-id-<?= $model->stage_id; ?>">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="name-stage">Этап: <?= $model->name; ?></h2>
        </div>
        <div class="col-lg-3">
            <?= $this->render('_action_buttons_output', compact('model')); ?>
        </div>
    </div>

    <div class="row" style="background-color: #eee">
        <div id="div-create-stage-output-ajax-stage-id-<?= $model->stage_id; ?>"></div>
    </div>
    <div class="row" style="background-color: #eee">

        <?php if ($outputs = $model->outputs) {

           // \backend\utils\D::dump(\yii\helpers\ArrayHelper::map($outputs,'output_id','name'));
            foreach ($outputs as $output) {
                echo $this->render("/output/_list_view", ['model' => $output]);

            }
        }; ?>

    </div>
</div>
<?php
?>

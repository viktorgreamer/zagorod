<?php

/* @var $this yii\web\View */
/* @var $model common\models\Output */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;

?>

<div class="output-list-view" id="div-output-id-<?= $model->output_id; ?>" style="background-color: #e1e1e1">
    <div class="row">
        <div class="col-lg-9">
            <div class="form-group">
                <?php echo Html::label($model->name, "#output_id_" . $model->output_id); ?>
                <?php

                echo Html::input('string', '', '',
                    [
                        'id' => "output_id_" . $model->output_id,
                        'size' => $model->width,
                        'width' => $model->width,
                        'class' => 'form-control',
                        'maxlength' => $model->width

                    ]
                );
                ?>
            </div>
        </div>
        <div class="col-lg-3" style="padding-right: 0px;">
            <?= $this->render('_action_buttons',compact('model')); ?>
        </div>
        <div class="row" style="background-color: #eee">
            <div id="div-update-stage-output-ajax-output-id-<?= $model->output_id; ?>"></div>
        </div>
    </div>

</div>



<?php
use yii\helpers\Html;
$outputValue = \common\models\OutputValue::find()->where(['output_id' => $model->output_id])->one();
?>


<div class="output-list-view" id="div-input-id-<?= $model->output_id; ?>" style="background-color: #e1e1e1">
    <div class="rows">
        <div class="col-lg-9">
            <div class="form-group">
                <?php echo Html::label($model->name, "#output_id_" . $model->output_id); ?>
                <?php

                echo Html::input('string', '', $outputValue->value,
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
        </div>

    </div>

</div>

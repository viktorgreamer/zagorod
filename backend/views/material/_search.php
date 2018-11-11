<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MaterialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'articul') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'complex_of_works') ?>

    <?= $form->field($model, 'measure') ?>

    <?php // echo $form->field($model, 'count') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'check') ?>

    <?php // echo $form->field($model, 'product_type') ?>

    <?php // echo $form->field($model, 'material_type') ?>

    <?php // echo $form->field($model, 'sg_sht') ?>

    <?php // echo $form->field($model, 'manufacturer') ?>

    <?php // echo $form->field($model, 'articul_man') ?>

    <?php // echo $form->field($model, 'type_cost') ?>

    <?php // echo $form->field($model, 'self_cost') ?>

    <?php // echo $form->field($model, 'link_to_numenclature') ?>

    <?php // echo $form->field($model, 'check1') ?>

    <?php // echo $form->field($model, 'r') ?>

    <?php // echo $form->field($model, 'name_station_bux') ?>

    <?php // echo $form->field($model, 'station_code') ?>

    <?php // echo $form->field($model, 'name_short') ?>

    <?php // echo $form->field($model, 'link') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

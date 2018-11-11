<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\bootstrap\Modal;
use common\models\BaseStationGroup;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStationSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="base-station-search">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-lg-1">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'articul') ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-lg-1">
            <?php echo $form->field($model, 'sp')->dropDownList([null => 'Нет'] + $model->mapSp()) ?>

        </div>
        <div class="col-lg-4">
            <?php echo $form->field($model, 'groups_id')->dropDownList($model->mapGroups(),['multiple' => true])->label('Группы') ?>

        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'type_calculate_id')->dropDownList([null => 'Нет'] + $model->mapCalculateType()) ?>
        </div>


        <!--
    <? /*= $form->field($model, 'measure') */ ?>

    --><? /*= $form->field($model, 'count') */ ?>
        <!--
            --><?php /* echo $form->field($model, 'price') */ ?>

        <?php // echo $form->field($model, 'cost') ?>

        <?php // echo $form->field($model, 'mark') ?>

        <?php // echo $form->field($model, 'performance') ?>

        <?php // echo $form->field($model, 'people') ?>

        <?php // echo $form->field($model, 'fecal_nas') ?>



        <?php // echo $form->field($model, 'self_cost') ?>

        <?php // echo $form->field($model, 'montaj') ?>

        <?php // echo $form->field($model, 'pnr') ?>

        <?php // echo $form->field($model, 'rshm') ?>

        <?php // echo $form->field($model, 'yakor') ?>

        <?php // echo $form->field($model, 'length') ?>

        <?php // echo $form->field($model, 'width') ?>

        <?php // echo $form->field($model, 'height') ?>

        <?php // echo $form->field($model, 'utepl') ?>

        <?php // echo $form->field($model, 'water') ?>

        <?php // echo $form->field($model, 'sand_manual') ?>

        <?php // echo $form->field($model, 'sand_tech') ?>

        <?php // echo $form->field($model, 'cement_manual') ?>

        <?php // echo $form->field($model, 'cement_manual_pac') ?>

        <?php // echo $form->field($model, 'cement_tech') ?>

        <?php // echo $form->field($model, 'cement_tech_pac') ?>

        <?php // echo $form->field($model, 'pit_manual') ?>

        <?php // echo $form->field($model, 'pit_tech') ?>

        <?php // echo $form->field($model, 'gasket') ?>

        <?php // echo $form->field($model, 'with_chasers') ?>
        <div class="col-lg-2">
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

            </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>

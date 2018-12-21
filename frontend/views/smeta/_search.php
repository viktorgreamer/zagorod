<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\SmetaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="smeta-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-lg-1">
            <?= $form->field($model, 'smeta_id') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'show_copy')->checkbox() ?>
        </div>
        <?php if (Yii::$app->user->can('admin')) { ?>
        <div class="col-lg-3">
            <?= $form->field($model, 'user_id')->dropDownList([ 0 => 'Любой'] + ArrayHelper::map(User::find()->all(), 'id', 'fullName')); ?>
        </div>

        <?php } ?>
        <div class="col-lg-2">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
            </div>

        </div>
    </div>






    <?php ActiveForm::end(); ?>

</div>

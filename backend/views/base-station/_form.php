<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-station-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'articul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'measure')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prices')->widget(\unclead\multipleinput\MultipleInput::className(), [
        'max' => \common\models\Regions::find()->count(),
        'columns' => [
            [
                'name' => 'region_id',
                'type' => 'dropDownList',
                'title' => 'Регион',
                'items' => \yii\helpers\ArrayHelper::map(\common\models\Regions::find()->all(), 'id', 'name'),
            ],
            [
                'name' => 'price',
                'title' => 'Цена клиента',
                'enableError' => true,
            ],
            [
                'name' => 'cost',
                'title' => 'Себестоимость',
                'enableError' => true,
            ], [
                'name' => 'self_cost',
                'title' => 'Себестоимость2',
                'enableError' => true,
            ],
            [
                'name' => 'is_available',
                'type' => 'dropDownList',
                'title' => 'Доступность в регионе',
                'items' => [1 => 'Есть',0 => 'нет'],
            ],
        ]
    ]); ?>


    <?= $form->field($model, 'performance')->textInput() ?>

    <?= $form->field($model, 'people')->textInput() ?>

    <?= $form->field($model, 'fecal_nas')->textInput() ?>

    <?= $form->field($model, 'sp')->textInput() ?>

    <?= $form->field($model, 'deep')->textInput() ?>

    <?= $form->field($model, 'type_calculate_id')->textInput() ?>

    <?= $form->field($model, 'self_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'montaj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rshm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yakor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utepl')->textInput() ?>

    <?= $form->field($model, 'water')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sand_manual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sand_tech')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cement_manual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cement_manual_pac')->textInput() ?>

    <?= $form->field($model, 'cement_tech')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cement_tech_pac')->textInput() ?>

    <?= $form->field($model, 'pit_manual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pit_tech')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gasket')->textInput() ?>

    <?= $form->field($model, 'with_chasers')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

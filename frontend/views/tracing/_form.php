<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model common\models\Tracing */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="tracing-form container">
        <?php if ($model->id) {
            echo "<h4>Редактирование ветки ".$model->name."</h4>";
            $route = ['tracing/update', 'id' => $model->id];
        } else {
            echo "<h4>Создание ветки ".$model->name."</h4>";
            if ($parent = \common\models\Tracing::findOne($model->parent_id)) echo "<h5>В ветку ".$parent->name."</h5>";
            $route = ['tracing/create'];
        } ?>
        <?php $form = ActiveForm::begin(['action' => $route]); ?>

        <? /*= $form->field($model, 'smeta_id')->textInput() */ ?>


        <div class="row">
            <?= $form->field($model, 'parent_id')->hiddenInput()->label(false); ?>
            <div class="col-lg-2">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'diameter')->dropDownList($model->mapDiameters()) ?>

            </div>

            <div class="col-lg-2">
                <?= $form->field($model, 'in_ground')->textInput() ?>

            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'in_air')->textInput() ?>

            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'rizer')->textInput() ?>

            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'zaglushka')->checkbox() ?>

            </div>

        </div>
        <div class="row">
            <div class="col-lg-2">
                <?php echo $form->field($model, 'revisions')->widget(MultipleInput::className(), [

                    'addButtonOptions' => [
                        'class' => ' btn btn-success'],
                    'columns' => [[
                        'name' => 'value',
                        // 'data' => $model->getRevisionCascades()->select('value')->column(),


                    ]]]); ?>
            </div>
            <div class="col-lg-2">

                <?php echo $form->field($model, 'cascades')->widget(MultipleInput::className(), [

                    'addButtonOptions' => [
                        'class' => ' btn btn-success'],
                    'columns' => [[
                        'name' => 'value',
                        // 'data' => $model->getRevisionCascades()->select('value')->column(),


                    ]]]); ?>
            </div>
            <div class="col-lg-2">

                <?php echo $form->field($model, 'revisionCascades')->widget(MultipleInput::className(), [

                    'addButtonOptions' => [
                        'class' => ' btn btn-success'],
                    'columns' => [[
                        'name' => 'value',
                        // 'data' => $model->getRevisionCascades()->select('value')->column(),


                    ]]]); ?>
            </div>
            <div class="col-lg-2">

                <?php echo $form->field($model, 'turns')->widget(MultipleInput::className(), [
                    'addButtonOptions' => [
                        'class' => ' btn btn-success'],
                    'columns' => [[
                        'name' => 'value',
                        // 'id' => 'turns',
                        'type' => 'dropDownList',
                        //'data' => $model->getTurns()->select('value')->column(),
                        'items' => $model->mapTurns(),


                    ]]]); ?>
            </div>
            <div class="col-lg-2">

                <?php echo $form->field($model, 'floors')->widget(MultipleInput::className(), [
                    'addButtonOptions' => [
                        'class' => ' btn btn-success'],
                    'columns' => [[
                        'name' => 'value',
                        // 'id' => 'turns',
                        'type' => 'dropDownList',
                        //'data' => $model->getTurns()->select('value')->column(),
                        'items' => $model->mapFloors(),


                    ]]]); ?>
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php

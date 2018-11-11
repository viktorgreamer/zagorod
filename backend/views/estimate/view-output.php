<?php


use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\EstimateStage;
use common\models\Icons;
/* @var $this yii\web\View */
/* @var $model common\models\Estimate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны Сметы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-view container" >
    <?php echo Html::a(" ВХОДЯЩИЕ ДАННЫЕ",['estimate/view', 'id' => $model->estimate_id],['class' => 'btn btn-primary']); ?>
    <?php echo Html::a(" РАСЧЕТ",['estimate/view-output', 'id' => $model->estimate_id],['class' => 'btn btn-primary']); ?>

    <h1><?= Html::encode($this->title) ?></h1>

        <?= Html::a('Редактировать', ['update', 'id' => $model->estimate_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->estimate_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить смету?',
                'method' => 'post',
            ],
        ]) ?>
        <?php echo Html::button(Icons::ADD." этап",
            [
                'value' => \yii\helpers\Url::to(['estimate-stage/create-ajax', 'estimate_id' => $model->estimate_id,'type' => EstimateStage::TYPE_OUTPUT]),
                'class' => 'btn btn-success modal-button-create-estimate-stage',
                'data' => [
                    "estimate_id" => $model->estimate_id],

                //  'id' => 'modal-button-create-estimate-stage-ajax',
                //   'data-estimate_id' => $model->estimate_id
            ]
        ); ?>

    <?php

    ?>
    <?php echo $this->render("/output/_div_input_data", ['inputs' =>  $model->inputs]); ?>
       <?php
        \yii\bootstrap\Modal::begin([
            'header' => '<h3>Добавить/редактировать этап</h3>',
            'id' => "modal-estimate-id-".$model->estimate_id
            // 'footer' => 'Низ окна',
        ]);
        ?>
    <div id="modal-estimate-div-id-<?= $model->estimate_id; ?>"></div>
    <?php \yii\bootstrap\Modal::end(); ?>


    <? \yii\widgets\Pjax::begin(['id' => "pjax_id", 'timeout' => 5000]); ?>

    <? if ($stages = $model->stagesOutput) {
       // \backend\utils\D::dump(\yii\helpers\ArrayHelper::map($stages,'stage_id','name'));

        foreach ($stages as $stage) {

            if (Yii::$app->session->get('current_estimate_stage_id_admin') == $stage->stage_id) $active = true; else $active = false;
            $tabsItems[] = [
                'label' =>   $stage->name,
                'content' =>  $this->render('/estimate-stage/_list_view_output', ['model' => $stage]),
                'active' => $active
            ];

            //  echo $this->render('/estimate-stage/_list_view', ['model' => $stage]);
        }
    }
    echo \yii\bootstrap\Tabs::widget(['items' => $tabsItems]);
    \yii\widgets\Pjax::end();
    ?>

</div>

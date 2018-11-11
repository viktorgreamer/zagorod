<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EstimateStageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estimate Stages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-stage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Estimate Stage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'stage_id',
            'name',
            'estimate_id',
            'priority',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

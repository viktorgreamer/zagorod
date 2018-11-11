<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InputSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inputs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Input', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'input_id',
            'estimate_id',
            'name',
            'stage_id',
            'type',
            'validation_rule_id',
            //'multiple',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OutputSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Outputs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="output-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Output', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'output_id',
            'name',
            'formula',
            'estimate_id',
            'stage_id',
            //'width',
            //'priority',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

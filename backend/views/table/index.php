<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Table', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'table_id',
            'name',
            'event_id',
            'estimate.name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {edit}',
                'buttons' => [
                    'edit' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-edit"> </span>', $url, ['title' => 'Report']);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

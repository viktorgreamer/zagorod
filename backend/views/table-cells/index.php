<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TableCellsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Table Cells';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-cells-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Table Cells', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tr_id',
            'td_id',
            'value',
            'table_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

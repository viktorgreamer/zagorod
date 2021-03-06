<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InputControlsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Input Controls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-controls-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Input Controls', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'event.name',
           'input.name',
            'typeText',
            'value',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {copy}',
                'buttons' => [
                    'copy' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-copy"> </span>', $url, ['title' => 'Copy']);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

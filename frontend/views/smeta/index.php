<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SmetaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Smetas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smeta-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Smeta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //  'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'smeta_id',
            'name',
            'estimate.name',
            [
                'label' => 'Мастер',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user->username;
                },
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    $links[] = Html::a(Icons::ADD, ['/smeta/fill-data','smeta_id' => $model->smeta_id], ['target' => '_blank']);

                    return implode(" ", $links);
                },
            ],

            // 'user.username',
            'date:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {report} {reset}{for-test}',
                'buttons' => [
                    'report' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-file"> </span>', $url, ['title' => 'Report']);
                    },
                    'reset' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-refresh"> </span>', $url, ['title' => 'Reset']);
                    },'for-test' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-check"> </span>', $url, ['title' => 'Выбрать для тестов']);
                    }
                ]
            ]
        ],
    ]); ?>
</div>

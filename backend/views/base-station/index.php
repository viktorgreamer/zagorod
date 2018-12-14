<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use common\models\BaseStationGroup;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BaseStationSearch */
/* @var $model common\models\BaseStation */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'База по станциям';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-index">
    <div class="row">
        <?= Html::a('Добавить станцию', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Сгенерировать цены для станций', ['base-station/generate-prices'], ['class' => 'btn btn-success']) ?>

        <?php
        Modal::begin([
            'header' => Html::tag('h4', "Добавить в группы "),
            'toggleButton' => [
                'label' => 'Добавить в группы',
                'tag' => 'button',
                'class' => 'btn btn-success',],
        ]);
        $groups = BaseStationGroup::find()->all();
        foreach ($groups as $group) {
            echo "<br>" . Html::button($group->name, ['class' => 'btn btn-success add-base-stations-to-group', 'data' => ['group_id' => $group->group_id]]);
        }
        Modal::end(); ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <div class="row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //  'id',
                'articul',
                'name',
                [
                    'label' => 'Материалы',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->materials;
                    },
                ],
                // 'measure',
                [
                    'label' => 'Ед.изм.',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->mapMeasure()[$model->measure];
                    },
                ],
                'count',
                'price',
                'cost',
                'mark',
                'performance',
                'people',
                // 'fecal_nas',
                [
                    'label' => 'Фекал.нас.',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->mapFecalnas()[$model->fecal_nas];
                    },
                ],
                //  'sp',
                [
                    'label' => 'С\П',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->mapSp()[$model->sp];
                    },
                ],
                'deep',
                // 'type_calculate_id',
                [
                    'label' => 'Типа расчета',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->calculateType->name;
                    },
                ],
                'self_cost',
                'montaj',
                'pnr',
                'rshm',
                'yakor',
                'length',
                'width',
                'height',
                'utepl',
                'water',
                'sand_manual',
                'sand_tech',
                'cement_manual',
                'cement_manual_pac',
                'cement_tech',
                'cement_tech_pac',
                'pit_manual',
                'pit_tech',
                'gasket',
                'with_chasers',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

</div>

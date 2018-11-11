<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MaterialSearch */
/* @var $model common\models\Material */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Materials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'formulaName',
            'articul',
            'name',
           // 'complex_of_works',
            [
                'label' => 'Комплект работ',
                'format' => 'raw',
                'value' => function($model){
                   return $model->mapComplexOfWork()[$model->complex_of_works];
                },
            ],
          //   'measure',
            [
                'label' => 'Ед.изм.',
                'format' => 'raw',
                'value' => function($model){
                    return $model->mapMeasure()[$model->measure];
                },
            ],
            'count',
            'price',
            'cost',
            'check',
           // 'product_type',
            [
                'label' => 'Тип изделия',
                'format' => 'raw',
                'value' => function($model){
                    return $model->mapProductType()[$model->product_type];
                },
            ],
           // 'material_type',
             [
                'label' => 'Тип материала',
                'format' => 'raw',
                'value' => function($model){
                    return $model->mapMaterialType()[$model->material_type];
                },
            ],
          //  'sg_sht',
            [
                'label' => 'сг/шт и т.д.',
                'format' => 'raw',
                'value' => function($model){
                    return $model->mapSgSht()[$model->sg_sht];
                },
            ],
            'manufacturer',
            'articul_man',
            'type_cost',
            [
                'label' => 'Себестоимость тип',
                'format' => 'raw',
                'value' => function($model){
                    return $model->mapTypeCost()[$model->type_cost];
                },
            ],
            'self_cost',
           // 'link_to_numenclature',
            [
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function($model){
                   if ($model->link_to_numenclature) return Html::a('link',$model->link_to_numenclature,['target' => '_blank']);
                },
            ],
            'check1',
            'r',
            'name_station_bux',
            'station_code',
            'name_short',
           // 'link',
            [
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function($model){
                    if ($model->link)    return Html::a('link',$model->link,['target' => '_blank']);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

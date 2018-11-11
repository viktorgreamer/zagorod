<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Base Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    echo Html::button(Icons::ADD . " Материал",
        [
            'value' => \yii\helpers\Url::to(['base-station/add-material', 'station_id' => $model->id]),
            'class' => 'btn btn-success add-material-station',
            /*'data' => [
                "estimate_id" => $model->estimate_id],*/
        ]
    ); ?>

    <div id="add-material-to-station-div"></div>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'articul',
            'name',
            [
                'label' => 'Сопутствующие Материалы',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($materials = $model->materials) {
                        $body = '<table class="table table-bordered table-striped"><thead><td>Артикул</td><td>Наименование</td><td>Кол-во</td><td>Удалить</td></thead>';
                        foreach ($materials as $material) {
                            $removeButton = Html::button(Icons::REMOVE, ['class' => 'delete-material-from-station btn btn-danger btn-xs', 'data' => ['id' => $material->id]]);
                            $body .= "<tr><td>" . $material->articul . "</td><td>" . $material->material->name . "</td><td>" . $material->count . "</td><td>" . $removeButton . "</td></tr>";
                        }
                        $body .= "</table>";
                    }
                    return $body;
                },
            ],
            'measure',
            'count',
            'price',
            'cost',
            'mark',
        /*    'performance',
            'people',
            'fecal_nas',
            'sp',
            'deep',
            'type_calculate_id',
            'self_cost',
            'montaj',
            'pnr',
            'rshm',*/
            'yakor',
            'length',
            'width',
            'height',
       /*     'utepl',
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
            'with_chasers',*/
        ],
    ]) ?>

</div>




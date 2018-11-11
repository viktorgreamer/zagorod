<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Material */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-view">

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'articul',
            'name',
            'complex_of_works',
            'measure',
            'count',
            'price',
            'cost',
            'check',
            'product_type',
            'material_type',
            'sg_sht',
            'manufacturer',
            'articul_man',
            'type_cost',
            'self_cost',
            'link_to_numenclature',
            'check1',
            'r',
            'name_station_bux',
            'station_code',
            'name_short',
            'link',
        ],
    ]) ?>

</div>

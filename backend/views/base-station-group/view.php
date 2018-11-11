<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStationGroup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Base Station Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->group_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->group_id], [
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
            // 'group_id',
            'name',
            'description',
            [
                'label' => 'Сопутствующие Материалы',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($materials = $model->materials) {
                        $body = '<table class="table table-bordered table-striped"><thead><td>Артикул</td><td>Наименование</td><td>Кол-во</td><td>Удалить</td></thead>';
                        foreach ($materials as $material) {
                            $removeButton = Html::button(Icons::REMOVE, ['class' => 'delete-material-from-group btn btn-danger btn-xs', 'data' => ['id' => $material->id]]);
                            $body .= "<tr><td>" . $material->articul . "</td><td>" . $material->material->name . "</td><td>" . $material->count . "</td><td>" . $removeButton . "</td></tr>";
                        }
                        $body .= "</table>";
                    }
                    return $body;
                },
            ],
        ],
    ]) ?>

</div>


<?php
echo Html::button(Icons::ADD . " Материал",
    [
        'value' => \yii\helpers\Url::to(['base-station-group/add-material', 'group_id' => $model->group_id]),
        'class' => 'btn btn-success add-material-group',
        /*'data' => [
            "estimate_id" => $model->estimate_id],*/
    ]
); ?>

<div id="add-material-to-group-from"></div>



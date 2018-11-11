<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Output */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Outputs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="output-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->output_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->output_id], [
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
            'output_id',
            'name',
            'formula',
            'estimate_id',
            'stage_id',
            'width',
            'priority',
        ],
    ]) ?>

</div>

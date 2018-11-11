<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */

$this->title = $model->smeta_id;
$this->params['breadcrumbs'][] = ['label' => 'Smetas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smeta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->smeta_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->smeta_id], [
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
            'smeta_id',
            'estimate_id',
            'date',
        ],
    ]) ?>

</div>

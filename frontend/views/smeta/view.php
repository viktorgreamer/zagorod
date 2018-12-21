<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

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
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    $links[] = Html::a(Icons::EDIT , ['/smeta/fill-data', 'smeta_id' => $model->smeta_id], ['target' => '_blank', 'class' => 'btn btn-success']);
                    $links[] = Html::a(Icons::COPY , ['/smeta/copy', 'smeta_id' => $model->smeta_id], ['target' => '_blank', 'class' => 'btn btn-success']);
                    $links[] = Html::a(Icons::PDF , ['/smeta/report-pdf', 'smeta_id' => $model->smeta_id], ['target' => '_blank', 'class' => 'btn btn-success']);
                    $links[] = Html::a(Icons::EXCEL , ['/smeta/report-excel', 'smeta_id' => $model->smeta_id], ['target' => '_blank', 'class' => 'btn btn-success']);

                    return implode(" ", $links);
                },
            ],
            [
                'label' => 'Даты',
                'attribute' => 'user',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->dates;
                },
            ],
            [
                'label' => 'Копии',
                'attribute' => 'user',
                'format' => 'raw',
                'value' => function (\common\models\Smeta $model) {
                    return $model->renderCopies();
                },
            ],
            [
                'label' => 'Файлы',
                'attribute' => 'files',
                'format' => 'raw',
                'value' => function (\common\models\Smeta $model) {
                    return $model->renderFiles();
                },
            ],
        ],
    ]) ?>

</div>

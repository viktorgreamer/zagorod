<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BaseStationCalculateType */

$this->title = 'Create Base Station Calculate Type';
$this->params['breadcrumbs'][] = ['label' => 'Base Station Calculate Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-station-calculate-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

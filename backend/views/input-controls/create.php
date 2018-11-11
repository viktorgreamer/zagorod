<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InputControls */

$this->title = 'Create Input Controls';
$this->params['breadcrumbs'][] = ['label' => 'Input Controls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-controls-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

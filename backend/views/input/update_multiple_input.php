<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Input */

$this->title = "Добавить поле ввода в '" . $model->stage->name . "'";
$this->params['breadcrumbs'][] = ['label' => 'Поле ввода', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-create">


         <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_form_multiple', [
        'model' => $model,
    ]) ?>

</div>

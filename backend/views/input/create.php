<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Input */

$this->title = "Добавить поле ввода в '" . $model->stage->name . "'";
$this->params['breadcrumbs'][] = ['label' => 'Поле ввода', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
 ?>
<div class="input-create">


    <?php if (!Yii::$app->request->isAjax) { ?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

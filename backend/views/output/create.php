<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Output */

$this->title = 'Создать исходящие данные';
$this->params['breadcrumbs'][] = ['label' => 'Исходящие данные', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="output-create">

    <?php if (!Yii::$app->request->isAjax) { ?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?php } ?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

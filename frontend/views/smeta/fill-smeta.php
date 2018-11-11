<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Icons;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */
/* @var $event common\models\Events */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php if ($estimates = $model->estimates) {
    foreach ($estimates as $estimate) {
        $items = [];
        echo "<h3>" . $estimate->name . "</h3>";
        if ($estimate_stages = $estimate->stages) {
            foreach ($estimate_stages as $estimate_stage) {
                /*$links[] = Html::button($estimate_stage->name, [
                        'value' => \yii\helpers\Url::to(['smeta/fill-stage', 'stage_id' => $estimate_stage->stage_id]),
                        'class' => 'btn btn-success btn-xs fill-stage',
                    ]
                );*/

                $items[] = [
                    'label' => $estimate_stage->name,
                    'options' => [
                        'class' => 'li-stage',
                        'data' => ['stage_id' => $estimate_stage->stage_id],],

                    'url' => false,
                    'linkOptions' => ['value' => \yii\helpers\Url::to(['smeta/fill-stage', 'stage_id' => $estimate_stage->stage_id]),
                        'class' => 'fill-stage li-stage']
                ];
            }
            $items[] = [
                'label' => "Считать",
                'url' => ['/smeta/calculate', 'smeta_id' => $model->smeta_id],
                ['target' => '_blank', 'class' => 'btn btn-primary']
            ];
            echo Nav::widget(['items' => $items, 'options' => ['class' => 'nav-pills']]);

        }
    }
};

?>
    <div id='div-fill-stages'>
    </div>


<?php
if ($events = $estimate->events) {
    $js = '';
    foreach ($events as $event) {
$js .= $event->renderJS();
    }
}
$script = <<< JS
events = [];
$js
JS;
$this->registerJs($script, yii\web\View::POS_READY);



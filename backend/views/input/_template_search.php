<?php

$url = \yii\helpers\Url::to(['/input/load-ajax']);

// The widget
use kartik\select2\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

$model = new \common\models\Input();
$form = \yii\widgets\ActiveForm::begin();

echo $form->field($model, 'name')->widget(Select2::classname(), [
    'initValueText' => '', // set the initial display text
    'options' => ['placeholder' => 'Поиск id, названию'],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'language' => [
            'errorLoading' => new JsExpression("function () { return 'Ищем совпадения'; }"),
        ],
        'ajax' => [
            'url' => $url,
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(city) { return city.text; }'),
        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
    ],
]);
\yii\widgets\ActiveForm::end();

?>

<div id="load-input-links"></div>


<?php
$js = <<<JS
$(document).on('change','#input-name',function() {
  console.log("#works-name HAS CHANGED");
  value = $(this).val();
  $(document).find("#load-input-links").load('/admin/input/template?id=' + value);
})
JS;
Yii::$app->view->registerJs($js,\yii\web\View::POS_READY);

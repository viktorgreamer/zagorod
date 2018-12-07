<?php

$url = \yii\helpers\Url::to(['/output/load-ajax']);

// The widget
use kartik\select2\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

$model = new \common\models\Output();
$form = \yii\widgets\ActiveForm::begin();

echo $form->field($model, 'name')->widget(Select2::classname(), [
    'initValueText' => '', // set the initial display text
    'options' => ['placeholder' => 'Поиск id, названию'],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumOutputLength' => 1,
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

<div id="load-output-links"></div>


<?php
$js = <<<JS
$(document).on('change','#output-name',function() {
  console.log("#output-name HAS CHANGED");
  value = $(this).val();
  $(document).find("#load-output-links").load('/admin/output/template?id=' + value);
})
JS;
Yii::$app->view->registerJs($js,\yii\web\View::POS_READY);

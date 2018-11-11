<?php

use kartik\select2\Select2;

$model = new \common\models\Works();


$form = \yii\widgets\ActiveForm::begin();

echo $form->field($model, 'name')->widget(Select2::classname(), [
    'language' => 'ru',
    'data' => \yii\helpers\ArrayHelper::map(\common\models\Works::find()->all(), 'id', 'name'),
    'options' => ['placeholder' => '...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
\yii\widgets\ActiveForm::end();

?>

<div id="load-works-links"></div>


<?php
$js = <<<JS
$(document).on('change','#works-name',function() {
  console.log("#works-name HAS CHANGED");
  value = $(this).val();
  $(document).find("#load-works-links").load('/admin/works/template?id=' + value);
})
JS;
Yii::$app->view->registerJs($js,\yii\web\View::POS_READY);

<?php


use unclead\multipleinput\MultipleInput;


/* @var $this yii\web\View */
/* @var $model common\models\Input */
/* @var $form yii\widgets\ActiveForm */

?>


<?= $form->field($model, 'multi_input')->widget(MultipleInput::className(), [
    'max' => 4,
    'data' => $model->renderMultipleInput(),
    'columns' => [
        [
            'name' => 'name',
            'title' => 'Name',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ],
        [
            'name' => 'rule',
            'type' => 'dropDownList',
            'title' => 'Правило Валидации',
            'defaultValue' => 0,
            'items' => [0 => 'нет'] + $model->mapRules()
        ],
        [
            'name' => 'type',
            'type' => 'dropDownList',
            'title' => "Тип",
            'defaultValue' => 0,
            'items' => ['' => 'Текстовое поле', 'dropDownList' => "Выпадающий список"]
        ],

        [
            'name' => 'items',
            'title' => 'Список значений',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ]
        ,
        [
            'name' => 'title',
            'title' => 'Описание',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ],
        [
            'name' => 'is_relative',
            'title' => 'Зависимое поле',
            'type' => 'dropDownList',
            'defaultValue' => 0,
            'items' => [0 => 'Нет', 1 => "Да"]

        ]
    ]
]);
?>
<?php
use yii\bootstrap\Tabs;

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Фунции работы с числами',
            'content' => $this->render('_digits'),
            'active' => true,
        ],
        [
            'label' => 'Логичесикие операторы',
            'content' => $this->render('_logic')
        ],
        [
            'label' => 'Функции работы с массивами',
            'content' => $this->render('_array')
        ],


    ]
]);




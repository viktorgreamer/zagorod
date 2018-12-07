<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $input common\models\Input */
/* @var $form yii\widgets\ActiveForm */


echo Tabs::widget([
    'items' => [
        [
            'label' => 'Входящие данные',
            'content' => $this->render('/input/_template_search'),
            'active' => true
        ],
        [
            'label' => 'События',
            'content' => $this->render('/events/_template_search'),
        ],
        [
            'label' => 'Вычисления',
            'content' => $this->render('/output/_template_search'),
        ],
        [
            'label' => 'Стоимость работ',
            'content' =>  $this->render('/works/_template_search')
        ],[
            'label' => 'Материалы',
            'content' =>  $this->render('/material/_template_search')
        ],
        [
            'label' => 'Данные станции',
            'content' => $this->render('/base-station/_template')
        ]
    ]
]);


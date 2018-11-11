<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $input common\models\Input */
/* @var $form yii\widgets\ActiveForm */


echo Tabs::widget([
    'items' => [
        [
            'label' => 'Входящие данные',
            'content' => $this->render('_data_inputs', compact(['inputs'])),
            'active' => true
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


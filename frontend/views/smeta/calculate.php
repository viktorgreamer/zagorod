<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 20.09.2018
 * Time: 13:45
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Smeta */


if ($estimates = $model->estimates) {
    foreach ($estimates as $estimate) {

        if ($outputs = $estimate->outputs) {

            foreach ($outputs as $output) {
                echo $this->render('_output_list', ['model' => $output]);
            }
            //  \backend\utils\D::dump($outputs);
        }
    }
}


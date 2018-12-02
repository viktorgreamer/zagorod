<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 02.12.2018
 * Time: 10:29
 */

namespace console\controllers;

use backend\utils\D;
use common\models\Evaluator;

class ConsoleController extends \yii\console\Controller
{

    public function beforeAction($action)
    {
        \backend\utils\D::$isConsole = true;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionToggleClasses() {

        $cell = \common\models\TableCells::findOne(45);
        $cell->toggleClass('text-color-red');
         // $cell->toggleClass('text-color-red');
        $cell->toggleClass('text-center');
        $cell->toggleClass('text-9center');
        D::success($cell->classes);
    }

    public function actionTestInversion() {
        $result = Evaluator::makeBoolean('not(0)');
        D::dump($result['value']);
        D::dump($result['type']);
      //  D::dump(eval("!1"));
    }

}
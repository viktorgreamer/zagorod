<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.10.2018
 * Time: 13:12
 */

namespace common\widgets;


use yii\base\Widget;

class TraceWidget extends Widget
{

    public $smeta;

    public function run()
    {
        return $this->render('trace',['smeta' => $this->smeta]);
    }

}
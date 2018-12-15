<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 15.12.18
 * Time: 20:44
 */

namespace common\widgets;


use yii\base\Widget;

class MultipleInput extends Widget
{
    public $input;

    public function run() {

        return $this->render('_multipleInput',['model' => $this->input]);


}

}
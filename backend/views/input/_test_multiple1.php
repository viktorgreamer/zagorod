<?php

use backend\utils\D;

$model = \common\models\Input::findOne(176);

D::dump($model->relatedItemsGroups());

 echo \common\widgets\MultipleInput::widget(['input' => $model]);
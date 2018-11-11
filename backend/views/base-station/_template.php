<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */


$basetation = new \common\models\BaseStation();

$properties = $basetation->attributeLabels();
?>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        Параметр
    </tr>
    <tr>
        Действие
    </tr>
    </thead>
    <?php foreach ($properties as $property => $label) { ?>
        <tr>
            <td><?php echo $label ?></td>


            <td><?php echo \yii\helpers\Html::button("station.".$property,
                    ['class' => 'btn btn-success btn-xs add-input-to-formula',
                        'data' => [
                            'formula_link' => "station.".$property." = Станция->".$label]]); ?></td>
        </tr>
    <?php } ?>

</table>

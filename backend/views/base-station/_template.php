<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\BaseStation */

if ($smeta = \common\models\Smeta::forTest()) {
    $basetation = \common\models\BaseStation::findOne($smeta->loadStation()['station.id']);
}
if (!$basetation) {
    $basetation = new \common\models\BaseStation();
}


$properties = $basetation->attributeLabels();
?>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
    <td>
        Параметр
    </td>
    <td>
        Значения станции <?= $basetation->articul; ?>
    </td>
    <td>
        Действие
    </td>
    </tr>
    </thead>
    <?php foreach ($properties as $property => $label) { ?>
        <tr>
            <td><?php echo $label ?></td>
            <td><?php echo $basetation->$property ?></td>
          <td><?php echo \yii\helpers\Html::button("station.".$property,
                    ['class' => 'btn btn-success btn-xs add-input-to-formula',
                        'onclick' => "copyToClipboard('"."station.".$property."')",
                        'data' => [
                            'formula_link' => "station.".$property." = Станция->".$label]]); ?></td>

        </tr>
    <?php } ?>

</table>

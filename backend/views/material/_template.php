<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Icons;

/* @var $this yii\web\View */
/* @var $model common\models\Material */


$model = new \common\models\Material();

$properties = $model->attributeLabelsForFormula();
$name = '';
$link = "material_{id}_.";
if ($id) {
    if ($work = \common\models\Material::findOne($id)) {
        $link = preg_replace("/{id}/", $work->id, $link);
        $name = $work->name;
    }
}
?>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <td> Параметр</td>
        <td>
            Действие
        </td>
    </tr>
    </thead>
    <?php foreach ($properties as $property => $label) { ?>
        <tr>
            <td><?php echo $name . " -->" . $label ?></td>

            <td><?php echo \yii\helpers\Html::button($link . $property,
                    ['class' => 'btn btn-success btn-xs add-input-to-formula',
                        'onclick' => "copyToClipboard('".$link . $property."')",
                        'data' => [
                            'formula_link' => $link . $property . " = Материал->" . $name . " ".$label]]); ?></td>
        </tr>
    <?php } ?>

</table>

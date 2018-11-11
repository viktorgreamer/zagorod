<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Input;
use common\models\Icons;

?>
    <hr class="no-margin">
    <div class="stage-list-view" id="div-stage-id-<?= $model->stage_id; ?>">
        <div class="row">
            <div class="col-lg-9">
                <h2 class="name-stage">Этап: <?= $model->name; ?></h2>
            </div>
            <div class="col-lg-3">
                <?= $this->render('_action_buttons',compact('model')); ?>
            </div>
        </div>

        <div class="row" style="background-color: #eee">
            <?php if ($inputs = $model->inputs) {
                foreach ($inputs as $input) {
                    echo $this->render("/input/_list_view", ['model' => $input]);

                }
            }; ?>

        </div>
    </div>
<?php

?>

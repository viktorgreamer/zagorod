<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "output".
 *
 * @property int $output_id Данные
 * @property string $name
 * @property string $formula Формула
 * @property int $estimate_id Смета
 * @property int $stage_id Этап
 * @property int $width
 * @property int $priority
 */
class Output extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'output';
    }

    public function getInputs()
    {
        return $this->hasMany(Input::className(), ['input_id' => 'input_id'])
            ->viaTable(InputToOutput::tableName(), ['output_id' => 'output_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estimate_id', 'stage_id'], 'required'],
            [['estimate_id', 'stage_id', 'width', 'priority'], 'integer'],
            [['name', 'formula','result'], 'string', 'max' => 256],
        ];
    }

    public function reorderPriority($priority)
    {
        if ($priority == 'up') {
            if ($this->priority != 1) {
                $this->priority = $this->priority - 1;
                $input_down = Output::find()->where(['stage_id' => $this->stage_id])->andWhere(['priority' => $this->priority])->one();
                $input_down->priority = $input_down->priority + 1;

                $input_down->save();
            }

        } elseif ($priority == 'down') {
            $max = Input::find()->where(['stage_id' => $this->stage_id])->max('priority');
            if ($this->priority != $max) {
                $this->priority = $this->priority + 1;
                $input_up = Output::find()->where(['stage_id' => $this->stage_id])->andWhere(['priority' => $this->priority])->one();
                $input_up->priority = $input_up->priority - 1;
                $input_up->save();
            }

        }
        $this->save();


    }

    public function beforeValidate()
    {

        if (!$this->estimate_id) {
            $stage = EstimateStage::findOne($this->stage_id);
            $this->estimate_id = $stage->estimate_id;
        }
        return parent::beforeValidate();

    }

    public function debugSave()
    {
        if (!$this->save()) {
            $keys = array_keys($this->getErrors());
            foreach ($keys as $key) {
                D::alert($key . "=" . $this->$key);
            }
            D::dump($this->getErrors());
        }
        // $this->save();
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'output_id' => 'Данные',
            'name' => 'Название',
            'formula' => 'Формула',
            'estimate_id' => 'Смета',
            'stage_id' => 'Этап',
            'width' => 'Ширина',
            'priority' => 'Приоритет',
            'result' => 'Результат',
        ];
    }
}

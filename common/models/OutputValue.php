<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "output_value".
 *
 * @property int $id
 * @property int $smeta_id
 * @property int $stage_id
 * @property int $output_id
 * @property string $value
 */
class OutputValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'output_value';
    }

    public function getOutput() {
        return $this->hasOne(Output::className(),['output_id' => 'output_id']);
    }

    public function getStage() {
        return $this->hasOne(EstimateStage::className(),['stage_id' => 'stage_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'output_id'], 'required'],
            [['smeta_id', 'output_id','stage_id'], 'integer'],
            [['value'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smeta_id' => 'Smeta ID',
            'output_id' => 'Output ID',
            'value' => 'Значение',
            'stage.name' => 'Этап',
            'stage_id' => 'Stage_id',
        ];
    }
}

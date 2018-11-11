<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "smeta_events".
 *
 * @property int $id
 * @property int $event_id Событие
 * @property int $value Результат
 * @property int $smeta_id Смета
 * @property int $estimate_id
 * @property int $stage_id
 * @property int $input_id
 */
class SmetaEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smeta_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'value', 'smeta_id'], 'required'],
            [['event_id', 'value', 'smeta_id', 'estimate_id', 'stage_id', 'input_id'], 'integer'],
        ];
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['event_id' => 'event_id']);
    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Событие',
            'value' => 'Результат',
            'smeta_id' => 'Смета',
            'estimate_id' => 'Estimate ID',
            'stage_id' => 'Stage ID',
            'input_id' => 'Input ID',
        ];
    }
}

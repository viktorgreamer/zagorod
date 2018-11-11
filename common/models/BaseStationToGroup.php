<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "base_station_to_group".
 *
 * @property int $station_id Станция
 * @property int $group_id Группа
 */
class BaseStationToGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_station_to_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['station_id', 'group_id'], 'required'],
            [['station_id', 'group_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'station_id' => 'Станция',
            'group_id' => 'Группа',
        ];
    }
}

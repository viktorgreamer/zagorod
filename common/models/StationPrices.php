<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "station_prices".
 *
 * @property int $station_id
 * @property int $region_id
 * @property int $price
 * @property int $cost
 * @property int $self_cost
 * @property int $is_available
 */
class StationPrices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'station_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['station_id', 'region_id', 'price', 'cost', 'self_cost', 'is_available'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'station_id' => 'Station ID',
            'region_id' => 'Region ID',
            'price' => 'Price',
            'cost' => 'Cost',
            'self_cost' => 'Self Cost',
            'is_available' => 'Is Available',
        ];
    }
}

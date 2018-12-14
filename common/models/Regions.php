<?php

namespace common\models;

use Yii;
use backend\utils\D;

/**
 * This is the model class for table "regions".
 *
 * @property int $id id
 * @property string $name Название
 * @property string $tc Коэф. утепления
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
    }


    public function generatePrices($station_id = null)
    {

        /* @var $station BaseStation */
        /* @var $region Regions */

        $queryStation = BaseStation::find();
        if ($station_id) {
            $queryStation->andWhere(['station_id' => $station_id]);
        }
        D::success(" LOST  = " . $queryStation->count());

        if ($stations = $queryStation->limit(500)->all()) {
            foreach ($stations as $station) {
                D::success("STATION NAME = " . $station->name);
                if ($existed = StationPrices::find()->where(['station_id' => $station->id])->andWhere(['region_id' => $this->id])->one()) {
                    D::success(" PRICE FOR THIS STATION AND REGION EXISTS");
                } else {
                    D::alert(" PRICE FOR THIS STATION AND REGION DOESN'T EXIST");
                    $stationPrice = new StationPrices();
                    $stationPrice->price = $station->price;
                    $stationPrice->self_cost = $station->self_cost;
                    $stationPrice->cost = $station->cost;
                    $stationPrice->is_available = 1;
                    $stationPrice->region_id = $this->id;
                    $stationPrice->station_id = $station->id;
                   if (!$stationPrice->save()) D::dump($stationPrice->errors);

                }

            }

        }

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tc'], 'required'],
            [['tc'], 'number'],
            [['name', 'phone', 'email', 'site', 'site2'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Название',
            'tc' => 'Коэф. утепления',
            'phone' => 'Телефон',
            'site' => 'Сайт',
            'site2' => 'Доп. сайт.',
            'email' => 'Email',
        ];
    }
}

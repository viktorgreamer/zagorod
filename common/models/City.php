<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name Название
 * @property int $region_id Регион
 * @property int $sand_shipment Песок доставка
 * @property int $sand_cost Песок цена, куб
 * @property int $rubble_shipment Щебень доставка
 * @property int $rubble_cost Щебень цена, куб
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    public function mapRegions()
    {
        return ArrayHelper::map(Regions::find()->All(), 'id', 'name');
    }


    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'region_id'], 'required'],
            [['region_id', 'sand_shipment', 'sand_cost', 'rubble_shipment', 'rubble_cost'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'region_id' => 'Регион',
            'region.name' => 'Регион',
            'sand_shipment' => 'Песок доставка',
            'sand_cost' => 'Песок цена, куб',
            'rubble_shipment' => 'Щебень доставка',
            'rubble_cost' => 'Щебень цена, куб',
        ];
    }
}

<?php

namespace common\models;

use Yii;
use common\models\Material;
/**
 * This is the model class for table "material_to_station".
 *
 * @property int $station_id Станция
 * @property int $material_id Материал
 * @property int $id id
 * @property int $count Количество
 */
class MaterialToStation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material_to_station';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['station_id', 'articul','count'], 'required'],
            [['station_id'], 'integer'],
            [['articul'], 'string'],
        ];
    }

    public function getMaterial() {
        return $this->hasOne(Material::className(),['articul' => 'articul']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count' => 'Кол-во',
            'station_id' => 'Станция',
            'Артикул' => 'Артикул',
            'id' => 'id',
        ];
    }
}

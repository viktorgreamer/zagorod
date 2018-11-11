<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;

/**
 * This is the model class for table "base_station".
 *
 * @property int $id id
 * @property string $articul артикул
 * @property string $name Наименование
 * @property int $measure ед.изм.
 * @property int $count кол-во
 * @property int $price Цена
 * @property int $cost Стоимость
 * @property string $mark Марка
 * @property double $performance Производительность
 * @property int $people Кол-во человек
 * @property int $fecal_nas Фекал. нас.
 * @property int $sp с\п
 * @property int $deep Глубина трубы
 * @property int $type_calculate_id Используется для расчетов
 * @property int $self_cost Себестоимость
 * @property int $montaj Монтаж
 * @property int $pnr ПНР
 * @property int $rshm РШМ
 * @property int $yakor Якорение
 * @property string $length Длина
 * @property string $width Ширина
 * @property string $height Высота
 * @property int $utepl Утеплитель
 * @property string $water Вода
 * @property string $sand_manual В ручную
 * @property string $sand_tech Техника
 * @property string $cement_manual цемент в ручную
 * @property int $cement_manual_pac
 * @property string $cement_tech цемент техника
 * @property int $cement_tech_pac
 * @property string $pit_manual Котлован в ручную
 * @property string $pit_tech Котлован техника
 * @property int $gasket Прокладка тр.
 * @property string $with_chasers Ширина грунтозацепов
 */
class BaseStation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_station';
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

    public function getMaterials()
    {
        return $this->hasMany(MaterialToStation::className(), ['station_id' => 'id']);
    }


    public function mapMeasure()
    {
        return [
            0 => 'шт.',
        ];
    }

    public function mapCalculateType()
    {
        return ArrayHelper::map(BaseStationCalculateType::find()->all(),'id','name');
    }

    public function mapGroups()
    {
        return ArrayHelper::map(BaseStationGroup::find()->all(),'group_id','name');
    }

    public function getCalculateType()
    {
        return $this->hasOne(BaseStationCalculateType::className(), ['id' => 'type_calculate_id']);
    }
    public function getGroups()
    {
        return $this->hasMany(BaseStationGroup::className(), ['group_id' => 'group_id'])
            ->viaTable(BaseStationToGroup::tableName(), ['station_id' => 'id']);
    }



    public function getGroupsId()
    {
        return $this->hasMany(BaseStationToGroup::className(), ['station_id' => 'id']);
    }

    public function mapFecalnas()
    {
        return [
            0 => '',
            1 => 'Н',
        ];
    }

    public function mapSp()
    {
        return [
            1 => 'С',
            2 => 'П',
        ];
    }

    public static function numericProperties()
    {
        return ['performance', 'length', 'width', 'height', 'water', 'sand_manual', 'sand_tech', 'cement_manual', 'cement_tech', 'pit_manual', 'pit_tech'];

    }

    public static function integerProperties()
    {
        return ['measure', 'count', 'price', 'cost', 'people', 'fecal_nas', 'sp', 'deep', 'type_calculate_id', 'self_cost', 'montaj', 'pnr', 'rshm', 'yakor', 'utepl', 'cement_manual_pac', 'cement_tech_pac', 'gasket'];

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['measure', 'count', 'price', 'cost', 'people', 'fecal_nas', 'sp', 'deep', 'type_calculate_id', 'self_cost', 'montaj', 'pnr', 'rshm', 'yakor', 'utepl', 'cement_manual_pac', 'cement_tech_pac', 'gasket'], 'integer'],
            [['performance', 'length', 'width', 'height', 'water', 'sand_manual', 'sand_tech', 'cement_manual', 'cement_tech', 'pit_manual', 'pit_tech'], 'number'],
            [['articul'], 'string', 'max' => 50],
            [['name', 'mark'], 'string', 'max' => 256],
            [['with_chasers'], 'string', 'max' => 10],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'articul' => 'артикул',
            'name' => 'Наименование',
            'measure' => 'ед.изм.',
            'count' => 'кол-во',
            'price' => 'Цена',
            'cost' => 'Стоимость',
            'mark' => 'Марка',
            'performance' => 'Производ.',
            'people' => 'Кол-во человек',
            'fecal_nas' => 'Фекал. нас.',
            'sp' => 'с\\п',
            'deep' => 'Глубина трубы',
            'type_calculate_id' => 'Используется для расчетов',
            'self_cost' => 'Себестоимость',
            'montaj' => 'Монтаж',
            'pnr' => 'ПНР',
            'rshm' => 'РШМ',
            'yakor' => 'Якорение',
            'length' => 'Длина',
            'width' => 'Ширина',
            'height' => 'Высота',
            'utepl' => 'Утеплитель',
            'water' => 'Вода',
            'sand_manual' => 'В ручную',
            'sand_tech' => 'Техника',
            'cement_manual' => 'цемент в ручную',
            'cement_manual_pac' => 'Cement Manual Pac',
            'cement_tech' => 'цемент техника',
            'cement_tech_pac' => 'Cement Tech Pac',
            'pit_manual' => 'Котлован в ручную',
            'pit_tech' => 'Котлован техника',
            'gasket' => 'Прокладка тр.',
            'with_chasers' => 'Ширина грунтозацепов',
        ];
    }
}

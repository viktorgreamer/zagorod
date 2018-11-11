<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "material".
 *
 * @property int $id id
 * @property string $articul Артикул
 * @property string $name Наименование
 * @property int $complex_of_works Комплект работ
 * @property int $measure Ед.изм.
 * @property int $count Кол-во
 * @property int $price Цена
 * @property int $cost Стоимость
 * @property int $check Проверка
 * @property int $product_type Тип изделия
 * @property int $material_type Тип материала
 * @property int $sg_sht сг/шт и т.д.
 * @property string $manufacturer  Производитель
 * @property string $articul_man Артикул производителя
 * @property int $type_cost Себестоимость тип
 * @property int $self_cost Себестоимость
 * @property string $link_to_numenclature Ссылка на нуменклатуру
 * @property string $check1 Проверка
 * @property string $r р
 * @property string $name_station_bux Наименование станции бух
 * @property string $station_code Код станциии
 * @property string $name_short Короткое наименование
 * @property string $link Ссылка
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material';
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


    public function mapMeasure()
    {
        return [
            0 => '',
            1 => 'м2',
            2 => 'м.',
            3 => 'м',
            4 => 'лист',
            5 => 'упак.',
            6 => 'шт',
            7 => 'компл.',
            8 => 'м3',
            9 => 'литр',
            10 => 'мешок',
            11 => 'кг',
            12 => 'шт.',
        ];
    }

    public function mapSgSht()
    {
        return [
            0 => '',
            1 => 'шт',
            2 => 'сг',
            3 => 'нестанд',
            4 => 'коллект',
            5 => 'латун',
        ];
    }

    public function mapTypeCost()
    {
        return [
            0 => '',
            1 => "аварийка",
            2 => "закупка",
            3 => "бух",
            4 => "материал",
            5 => "стоимость",
            6 => "насос дренажный",
            7 => "Астра",
            8 => "БиоДека",
            9 => "Топас",
            10 => "БиоПурит",
            11 => "Коло Веси",
            12 => "росток",
            13 => "ЕвроБион",
            14 => "Valtec",
            15 => "Genesis",
            16 => "ЕвроЛос",
            17 => "Тверь до 1м3",
            18 => "Тверь сверх 1 м3",
            19 => "Термит",
            20 => "Ergobox",
            21 => "Кристалл",
            22 => "Танк",
            23 => "мат",
            24 => "THERMEX",
            25 => "Гейзер",
            26 => "себест",
            27 => "прочие септики",

        ];
    }

    public function mapComplexOfWork()
    {
        return [
            0 => '',
            1 => 'НК',
            2 => 'К',
            3 => 'ВК',
            4 => 'К, В',
            5 => 'В',
            6 => 'НВ',
            7 => 'ВВ.',
            8 => 'д'
        ];
    }

    public function mapMaterialType()
    {
        return [
            0 => '',
            1 => "к",
            2 => "пнд",
            3 => "х",
            4 => "ппр",
            5 => "ац",
            6 => "пнд*р",
            7 => "р",
            8 => "пресс*р",
            9 => "пресс",
            10 => "мп",
            11 => "сш.п",
            12 => "обж*р",
            13 => "обж",
            14 => "пресс*обж",
            15 => "ппр*р",
            16 => "краны",
            17 => "фитинг-р",

        ];
    }

    public function mapProductType()
    {
        return [
            1 => 'загл',
            2 => 'к',
            3 => 'м	',
            4 => 'о	',
            5 => 'т	',
            6 => 'тр',
            7 => 'У	',
            8 => 'кш',
            9 => 'станция',
            10 => 'в-р',
            11 => 'обв',
            12 => 'вентиль	',
            13 => 'ко',
            14 => 'коллект	',
            15 => 'н',
            16 => 'ф',
            17 => 'п',
            18 => 'шт',
            19 => 'фк',
            20 => 'Регул арм',
            21 => 'Вод.теп.пол	',
            22 => 'Эл.авт',
            23 => 'Рад.арм.	',
            24 => 'КИП',
            25 => 'Сист.модульн.монт.',
            26 => 'Насосн.оборуд.',
            27 => 'Арм.безоп.',
            28 => 'подв.гибк',
            29 => 'РБ',
            30 => 'приб.уч',

        ];
    }


    public static function integerProperties()
    {
        return ['complex_of_works', 'measure', 'count', 'price', 'cost', 'check', 'product_type', 'material_type', 'sg_sht', 'type_cost', 'self_cost'];

    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['complex_of_works', 'measure', 'count', 'price', 'cost', 'check', 'product_type', 'material_type', 'sg_sht', 'type_cost', 'self_cost'], 'integer'],
            [['articul', 'name', 'manufacturer', 'articul_man', 'check1', 'r', 'name_station_bux', 'station_code', 'name_short'], 'string', 'max' => 256],
            [['link_to_numenclature', 'link'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'complex_of_works' => 'Комплект работ',
            'name' => 'Наименование',
            'measure' => 'Ед.изм.',
            'count' => 'Кол-во',
            'price' => 'Цена',
            'cost' => 'Стоимость',
            'check' => 'Проверка',
            'product_type' => 'Тип изделия',
            'material_type' => 'Тип материала',
            'sg_sht' => 'сг/шт и т.д.',
            'articul' => 'Артикул',
            'manufacturer' => ' Производитель',
            'articul_man' => 'Артикул производителя',
            'type_cost' => 'Себестоимость тип',
            'self_cost' => 'Себестоимость',
            'link_to_numenclature' => 'Ссылка на нуменклатуру',
            'check1' => 'Проверка',
            'r' => 'р',
            'name_station_bux' => 'Наименование станции бух',
            'station_code' => 'Код станциии',
            'name_short' => 'Короткое наименование',
            'link' => 'Ссылка',
        ];
    }
}

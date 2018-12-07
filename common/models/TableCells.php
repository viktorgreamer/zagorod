<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "table_cells".
 *
 * @property int $id
 * @property int $tr_id
 * @property int $td_id
 * @property int $value
 * @property string $address
 * @property int $table_id
 * @property int $type
 * @property string $classes
 */
class TableCells extends \yii\db\ActiveRecord
{
    public static $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    const H1 = "header1";
    const H1_pattern = "<h1>{value}</h1>";
    const H4 = "header4";
    const H4_pattern = "<h4>{value}</h4>";
    const CENTER = 'text-center';
    const RIGHT = 'text-right';
    const LEFT = 'text-left';
    const BOLD = 'text-bold';
    const CURSIVE = 'text-cursive';

    const FORMULA_TYPE = 1;
    const TEXT_TYPE = 2;

    public static $conflictClassess = [
        self::LEFT => [self::RIGHT, self::CENTER],
        self::CENTER => [self::RIGHT, self::LEFT],
        self::RIGHT => [self::CENTER, self::LEFT],
        self::H1 => [self::H4],
        self::H4 => [self::H1],
        self::BOLD => [],
        self::CURSIVE => [],
    ];

    public static $formatMessages = [
        self::LEFT => Icons::ALIGH_LEFT,
        self::CENTER => Icons::ALIGH_CENTER,
        self::RIGHT => Icons::ALIGH_RIGHT,
        self::H1 => "H1",
        self::H4 => "H4",
        self::BOLD => Icons::TEXT_BOLD,
        self::CURSIVE => 'Курсив',
    ];

    public static function formatText($format)
    {
        return self::$formatMessages[$format];
    }

    public function generateAddress()
    {
        return self::$letters[$this->td_id - 1] . $this->tr_id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_cells';
    }

    public function removableClass($format)
    {
        return self::$conflictClassess[$format];
    }

    public function toggleClass($format = '')
    {
        if ($format) {
            if ($this->classes) {

                $classess = explode(" ", $this->classes);
                if ($removableClasses = $this->removableClass($format)) {
                    $classess = array_diff($classess, $removableClasses);
                }

                // вычитаем конфликтующие элементы
                D::success("CLASSESS ARE ");
                D::dump($classess);
                if (in_array($format, $classess)) unset($classess[array_search($format, $classess)]);
                else array_push($classess, $format);
                $this->classes = implode(" ", $classess);
            } else {
                D::alert("NO CLASSESS");

                $this->classes = $format;
            }
        } else {
            $this->classes = '';
        }


    }
    public function removeClass($format = '')
    {
        if ($format) {
            if ($this->classes) {

                $classess = explode(" ", $this->classes);
                if ($removableClasses = $this->removableClass($format)) {
                    $classess = array_diff($classess, $removableClasses);
                }

                // вычитаем конфликтующие элементы
                D::success("CLASSESS ARE ");
                D::dump($classess);
                if (in_array($format, $classess)) unset($classess[array_search($format, $classess)]);

                $this->classes = implode(" ", $classess);
            } else {
                D::alert("NO CLASSESS");

                $this->classes = $format;
            }
        }


    }

    public function addClass($format = '')
    {
        if ($format) {
            if ($this->classes) {

                $classess = explode(" ", $this->classes);
                if ($removableClasses = $this->removableClass($format)) {
                    $classess = array_diff($classess, $removableClasses);
                }

                // вычитаем конфликтующие элементы
                D::success("CLASSESS ARE ");
                D::dump($classess);
                if (!in_array($format, $classess))  array_push($classess, $format);
                $this->classes = implode(" ", $classess);
            } else {
                D::alert("NO CLASSESS");

                $this->classes = $format;
            }
        } else {
            $this->classes = '';
        }


    }

    public function getClassesArray()
    {
        return explode(" ", $this->classes);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tr_id', 'td_id', 'table_id', 'type'], 'integer'],
            [['classes', 'align', 'value', 'address'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tr_id' => 'Tr ID',
            'td_id' => 'Td ID',
            'value' => 'Value',
            'table_id' => 'Table ID',
        ];
    }

    public function getRows()
    {

    }

    public function beforeSave($insert)
    {
        if (!$this->address) $this->address = $this->generateAddress();
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}

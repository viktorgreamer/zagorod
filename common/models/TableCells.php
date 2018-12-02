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
 * @property int $table_id
 * @property string $classes
 */
class TableCells extends \yii\db\ActiveRecord
{

    const H1 = "header1";
    const H1_pattern = "<h1>{value}</h1>";
    const H4 = "header4";
    const H4_pattern = "<h4>{value}</h4>";
    const CENTER = 'text-center';
    const RIGHT = 'text-right';
    const LEFT = 'text-left';
    const BOLD = 'text-bold';
    const CURSIVE = 'text-cursive';

    public static $conflictClassess = [
        self::LEFT => [self::RIGHT,self::CENTER],
        self::CENTER => [self::RIGHT,self::LEFT],
        self::RIGHT => [self::CENTER,self::LEFT],
        self::H1 => [self::H4],
        self::H4 => [self::H1],
       /* self::BOLD => [self::CURSIVE],
        self::CURSIVE => [self::BOLD],*/
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_cells';
    }
    public function removableClass($format) {
        return self::$conflictClassess[$format];
    }

    public function toggleClass($format = '')
    {
        if ($format) {
            if ($this->classes) {
                $classess = array_diff(explode(" ", $this->classes),$this->removableClass($format));

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
        }


    }

    public function getClassesArray() {
        return explode(" ", $this->classes);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tr_id', 'td_id', 'table_id'], 'integer'],
            [['classes','align','value'], 'string'],
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
}

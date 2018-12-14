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
 * @property string $fillColor
 */
class TableCells extends \yii\db\ActiveRecord
{

    public static $colors = [
        '000000', '000033', '000066', '000099', '0000cc', '0000ff', '003300', '003333', '003366',
        '003399', '0033cc', '0033ff', '006600', '006633', '006666', '006699', '0066cc', '0066ff',
        '009900', '009933', '009966', '009999', '0099cc', '0099ff', '00cc00', '00cc33', '00cc66',
        '00cc99', '00cccc', '00ccff', '00ff00', '00ff33', '00ff66', '00ff99', '00ffcc', '00ffff',
        '330000', '330033', '330066', '330099', '3300cc', '3300ff', '333300', '333333', '333366',
        '333399', '3333cc', '3333ff', '336600', '336633', '336666', '336699', '3366cc', '3366ff',
        '339900', '339933', '339966', '339999', '3399cc', '3399ff', '33cc00', '33cc33', '33cc66',
        '33cc99', '33cccc', '33ccff', '33ff00', '33ff33', '33ff66', '33ff99', '33ffcc', '33ffff',
        '660000', '660033', '660066', '660099', '6600cc', '6600ff', '663300', '663333', '663366',
        '663399', '6633cc', '6633ff', '666600', '666633', '666666', '666699', '6666cc', '6666ff',
        '669900', '669933', '669966', '669999', '6699cc', '6699ff', '66cc00', '66cc33', '66cc66',
        '66cc99', '66cccc', '66ccff', '66ff00', '66ff33', '66ff66', '66ff99', '66ffcc', '66ffff',
        '990000', '990033', '990066', '990099', '9900cc', '9900ff', '993300', '993333', '993366',
        '993399', '9933cc', '9933ff', '996600', '996633', '996666', '996699', '9966cc', '9966ff',
        '999900', '999933', '999966', '999999', '9999cc', '9999ff', '99cc00', '99cc33', '99cc66',
        '99cc99', '99cccc', '99ccff', '99ff00', '99ff33', '99ff66', '99ff99', '99ffcc', '99ffff',
        'cc0000', 'cc0033', 'cc0066', 'cc0099', 'cc00cc', 'cc00ff', 'cc3300', 'cc3333', 'cc3366',
        'cc3399', 'cc33cc', 'cc33ff', 'cc6600', 'cc6633', 'cc6666', 'cc6699', 'cc66cc', 'cc66ff',
        'cc9900', 'cc9933', 'cc9966', 'cc9999', 'cc99cc', 'cc99ff', 'cccc00', 'cccc33', 'cccc66',
        'cccc99', 'cccccc', 'ccccff', 'ccff00', 'ccff33', 'ccff66', 'ccff99', 'ccffcc', 'ccffff',
        'ff0000', 'ff0033', 'ff0066', 'ff0099', 'ff00cc', 'ff00ff', 'ff3300', 'ff3333', 'ff3366',
        'ff3399', 'ff33cc', 'ff33ff', 'ff6600', 'ff6633', 'ff6666', 'ff6699', 'ff66cc', 'ff66ff',
        'ff9900', 'ff9933', 'ff9966', 'ff9999', 'ff99cc', 'ff99ff', 'ffcc00', 'ffcc33', 'ffcc66',
        'ffcc99', 'ffcccc', 'ffccff', 'ffff00', 'ffff33', 'ffff66', 'ffff99', 'ffffcc', 'ffffff'];

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
    const FILL_RED = 'fill-red';
    const FILL_GREEN = 'fill-green';
    const FILL_BLUE = 'fill-blue';


    const FORMULA_TYPE = 1;
    public static $conflictClassess = [
        self::LEFT => [self::RIGHT, self::CENTER],
        self::CENTER => [self::RIGHT, self::LEFT],
        self::RIGHT => [self::CENTER, self::LEFT],
        self::H1 => [self::H4],
        self::H4 => [self::H1],
        self::BOLD => [],
        self::CURSIVE => [],
        /*     self::FILL_RED => [self::FILL_GREEN, self::FILL_BLUE],
             self::FILL_BLUE => [self::FILL_GREEN, self::FILL_RED],
             self::FILL_GREEN => [self::FILL_RED, self::FILL_BLUE],
        */
    ];

    const TEXT_TYPE = 2;

    public function setColor($color)
    {
        $this->fillColor = $color;
    }

    public static function countRows($value, $width)
    {
        // $sense = [];
        $string = [];
        $countRows = 1;
        if ($words = preg_split("/\s/", $value)) {
            foreach ($words as $word) {
                if (mb_strlen(implode(" ", $string) . " " . $word) > $width) {
                    //  D::alert(" LEN  = " . mb_strlen(implode(" ", $string) . " " . $word));
                    //  D::primary($string);
                    // $sense[] = implode(" ",$string);
                    $string = [];
                    $string[] = $word;
                    $countRows++;

                } else {
                    $string[] = $word;
                }

            }
            return $countRows;
        } else return 0;


    }

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
                if (!in_array($format, $classess)) array_push($classess, $format);
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

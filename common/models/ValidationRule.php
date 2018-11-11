<?php

namespace common\models;

use backend\utils\D;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "global_validation_rule".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $max
 * @property int $min
 * @property string $preg_match
 * @property string $mask
 * @property int $required
 */
class ValidationRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    //  public $required = true;
    const STRING_TYPE = 1;
    const INTEGER_TYPE = 2;
    const FLOAT_TYPE = 3;
    const BOOLEAN_TYPE = 4;
    const IN_ARRAY_TYPE = 5;
    const PREG_MATCH_TYPE = 6;
    const IN_LIST_TYPE = 7;
    const EMAIL_TYPE = 8;

    const PHONENUMBER_TYPE = 9;

    // errors fo validation
    const IS_EMPTY = 'empty_value';
    const MAX_LEN = 'max_len';
    const MIN_LEN = 'min_len';
    const MAX = 'max';
    const MIN = 'min';
    const NOT_STRING = 'not_string';
    const NOT_INTEGER = 'not_integer';
    const NOT_FLOAT = 'not_float';
    const NOT_IN_ARRAY = 'not_in_values';
    const NOT_PREG_MATCH = 'not_PREG_MATCH';
    const NOT_EMAIL = 'not_email';
    const NOT_PHONENUMBER = 'not_phonenumber';


    public static function tableName()
    {
        return 'validation_rule';
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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'max', 'min', 'required'], 'integer'],
            [['preg_match', 'name', 'mask'], 'string', 'max' => 256],
        ];
    }

    public function mapRules()
    {
        return ArrayHelper::map(ValidationRule::find()->all(), 'id', 'name');
    }


    public function check_type($value)
    {
        switch ($this->type) {
            case self::STRING_TYPE:
                {
                    if (!is_string($value)) return self::NOT_STRING;
                    return true;
                }
            case self::INTEGER_TYPE:
                {
                    D::echor(" VALUE = " . $value . " (int) value =" . (int)$value);
                    // if (strval((int)$value) !== strval($value)) return self::NOT_INTEGER;
                    if (!filter_var($value, FILTER_VALIDATE_INT)) return self::NOT_INTEGER;
                    return true;
                }
            case self::FLOAT_TYPE:
                {
                    D::echor(" VALUE = " . $value . " (float) value =" . (float)$value);
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) return self::NOT_FLOAT;
                    return true;
                }

            case self::EMAIL_TYPE:
                {
                    $value = trim($value);
                    if ($value != filter_var($value, FILTER_VALIDATE_EMAIL)) return self::NOT_EMAIL;
                    return true;

                }
            default:
                return true;
        }
    }

    public function check_value($value)
    {


        switch ($this->type) {
            case self::STRING_TYPE:
                {

                    if (isset($this->max)) {
                        if (strlen($value) > $this->max) return self::MAX_LEN;
                    }
                    if (isset($this->min)) {
                        if (strlen($value) < $this->min) return self::MIN_LEN;
                    }
                    return true;
                }

            case self::INTEGER_TYPE:
                {
                    if (isset($this->max)) {
                        if ($value > $this->max) return self::MAX;
                    }
                    if (isset($this->min)) {
                        if ($value < $this->min) return self::MIN;
                    }
                    return true;

                }
            case self::FLOAT_TYPE:
                {
                    if (isset($this->max)) {
                        if ($value > $this->max) return self::MAX;
                    }
                    if (isset($this->min)) {
                        if ($value < $this->min) return self::MIN;
                    }
                    return true;

                }
            case self::IN_ARRAY_TYPE:
                {
                    if (!in_array($value, explode(',', $this->preg_match))) return self::NOT_IN_ARRAY;
                    return true;

                }
            case self::PREG_MATCH_TYPE:
                {
                    $pattern = ($this->preg_match);
                    D::echor($pattern);
                    if (!preg_match("/" . $this->preg_match . "/", $value)) return self::NOT_PREG_MATCH;
                    return true;

                }

            default:
                return true;
        }
    }

    public
    function check($value)
    {
        if (($this->required !== 0) && (empty($value))) return self::IS_EMPTY;
        if ((empty($value)) && (!$this->required)) return true;

        if (($check_type = $this->check_type($value)) === true) {
          //  D::success(" VALIDATION TYPE IS SUCCESSFULL");

            if (($check_value = $this->check_value($value)) === true) {
           //     D::success(" VALIDATION OF VALUE IS SUCCESSFULL");
                return true;
            } else {
             //   D::alert(" VALIDATION OF VALUE IS FAIL");
                return $check_value;

            }

        } else {
          //  D::alert(" VALIDATION TYPE IS FAIL");
          //  D::alert(gettype($value));
            return $check_type;
        }

    }

    public
    function mapTypes()
    {
        return [
            self::STRING_TYPE => "Строка",
            self::INTEGER_TYPE => "Целое число",
            self::FLOAT_TYPE => "Число",
            self::BOOLEAN_TYPE => "Да/нет",
            self::IN_ARRAY_TYPE => "В списке значений",
            self::PREG_MATCH_TYPE => "Совпадение",
            self::EMAIL_TYPE => "E-mail"
        ];
    }

    public
    function renderRule($response = '')
    {
        $rule = [];

        if ($response == self::IS_EMPTY) return "Поле не может быть пустым";
        // $type_rule = $this->mapTypes()[$this->type];
        switch ($this->type) {
            case self::STRING_TYPE:
                {
                    $rule[] = "Строка";
                    if (($this->min) || ($this->max)) $rule[] = 'длинною';
                    if ($this->min) $rule[] = "от " . $this->min;
                    if ($this->max) $rule[] = "до " . $this->max;
                    break;
                }
            case self::INTEGER_TYPE:
                {

                    $rule[] = "Целое число";
                    if ($this->min) $rule[] = "от " . $this->min;
                    if ($this->max) $rule[] = "до " . $this->max;

                    break;
                }
            case self::FLOAT_TYPE:
                {
                    $rule[] = "Число";
                    if ($this->min) $rule[] = "от " . $this->min;
                    if ($this->max) $rule[] = "до " . $this->max;
                    break;
                }
            case self::EMAIL_TYPE:
                {
                    $rule[] = "Введите правильный E-mail";
                    break;
                }

            default :
                $rule[] = "Ошибка ввода данных";
                break;
        }


        return implode(" ", $rule);


    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type' => 'Тип',
            'max' => 'Max',
            'min' => 'Min',
            'preg_match' => 'Совпадение',
            'required' => 'Обязательность',
        ];
    }
}

<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "input_controls".
 *
 * @property int $id
 * @property int $event_id Событие
 * @property int $input_id
 * @property int $type Тип
 * @property string $value Значение
 */
class InputControls extends \yii\db\ActiveRecord
{

    const TYPE_SET_VALUE = 1;
    const TYPE_DISACTIVE = 2;
    const TYPE_CHECKED = 3;
    const TYPE_UNCHECKED = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'input_controls';
    }

    public function mapTypes()
    {
        return [
            self::TYPE_SET_VALUE => "Значение",
            self::TYPE_DISACTIVE => "Неактивно",
            self::TYPE_CHECKED => "Галочка нажата",
            self::TYPE_UNCHECKED => "Галочка не нажата"
        ];
    }

    public function mapEvents($extended = false)
    {
        if ($extended) {
            return ArrayHelper::map(Events::find()->all(), 'event_id', 'extendedName');
        } else return ArrayHelper::map(Events::find()->all(), 'event_id', 'name');
    }

    public function mapInputs($extended = false)
    {
        if ($extended) {
            return ArrayHelper::map(Input::find()->all(), 'input_id', 'ExtendedName');

        } else return ArrayHelper::map(Input::find()->all(), 'input_id', 'name');

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'input_id', 'type'], 'required'],
            [['event_id', 'input_id', 'type'], 'integer'],
            [['value'], 'string', 'max' => 256],
        ];
    }

    public function getInput()
    {
        return $this->hasOne(Input::className(),['input_id' => $this->input_id]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Событие',
            'input_id' => 'Поле ввода',
            'type' => 'Тип',
            'value' => 'Значение',
        ];
    }
}

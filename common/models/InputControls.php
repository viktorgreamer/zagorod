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
    public function getTypeText()
    {
       return $this->mapTypes()[$this->type];
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
            [['event_id', 'input_id', 'type','name'], 'required'],
            [['event_id', 'input_id', 'type'], 'integer'],
            [['value','name'], 'string', 'max' => 256],
        ];
    }

    public function getInput()
    {
        return $this->hasOne(Input::className(),['input_id' => 'input_id']);
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(),['event_id' => 'event_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event.name' => 'Событие',
            'input.name' => 'Поле ввода',
            'typeText' => 'Тип',
            'type' => 'Тип',
            'value' => 'Значение',
        ];
    }
}

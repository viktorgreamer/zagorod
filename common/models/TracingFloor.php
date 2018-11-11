<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tracing_floor".
 *
 * @property int $tracing_id
 * @property int $value
 */
class TracingFloor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tracing_floor';
    }

    public function mapTypes()
    {
        return [
            1 => "Дерево",
            2 => "Плитка",
            3 => "Кирпич",
            4 => "Газобетон",
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracing_id', 'value'], 'required'],
            [['tracing_id', 'value'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tracing_id' => 'Tracing ID',
            'value' => 'Value',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tracing_turn".
 *
 * @property int $tracing_id
 * @property int $value
 */
class TracingTurn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tracing_turn';
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

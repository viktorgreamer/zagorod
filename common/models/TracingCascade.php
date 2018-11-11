<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tracing_cascade".
 *
 * @property int $tracing_id
 * @property string $value
 */
class TracingCascade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tracing_cascade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracing_id', 'value'], 'required'],
            [['tracing_id'], 'integer'],
            [['value'], 'number'],
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

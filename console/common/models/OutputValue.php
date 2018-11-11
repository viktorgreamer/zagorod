<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "output_value".
 *
 * @property int $id
 * @property int $smeta_id
 * @property int $output_id
 * @property string $value
 */
class OutputValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'output_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'output_id'], 'required'],
            [['smeta_id', 'output_id'], 'integer'],
            [['value'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smeta_id' => 'Smeta ID',
            'output_id' => 'Output ID',
            'value' => 'Value',
        ];
    }
}

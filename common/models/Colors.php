<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "colors".
 *
 * @property int $id
 * @property string $hex
 * @property int $time
 */
class Colors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hex'], 'required'],
            [['time'], 'integer'],
            [['hex'], 'string', 'max' => 6,'min' => 6],
            [['hex'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hex' => 'Hex',
            'time' => 'Time',
        ];
    }
}

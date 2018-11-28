<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "table_columns".
 *
 * @property int $td_id
 * @property int $table_id
 * @property int $width
 */
class TableColumns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_columns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['td_id', 'table_id'], 'required'],
            [['td_id', 'table_id', 'width'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'td_id' => 'Td ID',
            'table_id' => 'Table ID',
            'width' => 'Width',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "table_cells".
 *
 * @property int $id
 * @property int $tr_id
 * @property int $td_id
 * @property int $value
 * @property int $table_id
 */
class TableCells extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_cells';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tr_id', 'td_id', 'value', 'table_id'], 'integer'],
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
}

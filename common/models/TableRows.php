<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "table_rows".
 *
 * @property int $tr_id
 * @property int $table_id
 * @property string $result
 */
class TableRows extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_rows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tr_id', 'table_id'], 'required'],
            [['tr_id', 'table_id'], 'integer'],
            [['result'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tr_id' => 'Tr ID',
            'table_id' => 'Table ID',
            'result' => 'Result',
        ];
    }
}

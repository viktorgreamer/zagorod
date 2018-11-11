<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_table".
 *
 * @property int $table_id
 * @property string $name
 * @property int $event_id
 */
class Table extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_table';
    }

    public function getRow() {

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'table_id' => 'Table ID',
            'name' => 'Name',
            'event_id' => 'Event ID',
        ];
    }
}

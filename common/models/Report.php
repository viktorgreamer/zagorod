<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report".
 *
 * @property int $report_id
 * @property int $smeta_id
 * @property string $name
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'name'], 'required'],
            [['smeta_id'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'report_id' => 'Report ID',
            'smeta_id' => 'Smeta ID',
            'name' => 'Name',
        ];
    }
}

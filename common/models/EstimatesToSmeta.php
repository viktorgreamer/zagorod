<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "estimates_to_smeta".
 *
 * @property int $id
 * @property int $estimate_id Шаблон
 * @property int $priority Приоритет
 * @property int $status Статус
 */
class EstimatesToSmeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estimates_to_smeta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estimate_id','smeta_id'], 'required'],
            [['estimate_id', 'priority','status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estimate_id' => 'Шаблон',
            'priority' => 'Приоритет',
            'status' => 'Статус',
        ];
    }
}

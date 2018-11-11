<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "input_to_output".
 *
 * @property int $id
 * @property int $input_id
 * @property int $output_id
 */
class InputToOutput extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'input_to_output';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['input_id', 'output_id'], 'required'],
            [['id', 'input_id', 'output_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'input_id' => 'Input ID',
            'output_id' => 'Output ID',
        ];
    }
}

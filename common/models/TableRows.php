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

    public function isActive(Smeta $smeta)
    {
        if ($this->result) {
            $result_value = $smeta->ReplaceValue($this->result);
            if ($result_value === $this->result) {

                // $table_row->result = "<text style=\"color:red\">" . $table_row->result . "</text>";
                return false;
            } else {
                $value = \common\models\Evaluator::makeBoolean($result_value);

                if (!$value['value']) {
                    return false;
                } else return true;

            }
        } else return true;
    }

    public function getCells()
    {
        return $this->hasMany(TableCells::className(), ['tr_id' => 'tr_id'])->andWhere(['table_id' => $this->table_id]);
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

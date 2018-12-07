<?php

namespace common\models;

use backend\utils\D;
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

    public function getRows($row_id = null)
    {
        return $this->hasMany(TableCells::className(), ['table_id' => 'table_id'])->andFilterWhere(['tr_id' => $row_id]);;
    }

    public function getEstimate() {
        return $this->hasOne(Estimate::className(),['estimate_id' => 'estimate_id']);
    }


    public function reorderPriority($tr_id, $priority)
    {
        if ($priority == 'up') $new_tr = $tr_id - 1; else $new_tr = $tr_id + 1;
        // ставим бывшему tr_id = 0 прячем в буфер.
        TableCells::updateAll([
                'tr_id' => 0
            ]
            , [
                'table_id' => $this->table_id,
                'tr_id' => $new_tr
            ]);

        TableRows::updateAll([
                'tr_id' => 0
            ]
            , [
                'table_id' => $this->table_id,
                'tr_id' => $new_tr
            ]);

        // перемещаем текущий tr_id
        TableCells::updateAll([
            'tr_id' => $new_tr
        ], [
            'table_id' => $this->table_id,
            'tr_id' => $tr_id
        ]);

        // перемещаем текущий tr_id
        TableRows::updateAll([
            'tr_id' => $new_tr
        ], [
            'table_id' => $this->table_id,
            'tr_id' => $tr_id
        ]);

        // ставим бывшему tr_id = 0 прячем в буфер.
        TableCells::updateAll([
            'tr_id' => $tr_id
        ],
            [
                'table_id' => $this->table_id,
                'tr_id' => 0
            ]);

        // ставим бывшему tr_id = 0 прячем в буфер.
        TableRows::updateAll([
            'tr_id' => $tr_id
        ],
            [
                'table_id' => $this->table_id,
                'tr_id' => 0
            ]);


    }

    public function reorderPriorityColumn($td_id, $priority)
    {
        if ($priority == 'left') $new_tr = $td_id - 1; else $new_tr = $td_id + 1;
        // ставим бывшему td_id = 0 прячем в буфер.
        TableCells::updateAll([
                'td_id' => 0
            ]
            , [
                'table_id' => $this->table_id,
                'td_id' => $new_tr
            ]);

        // перемещаем текущий td_id
        TableCells::updateAll([
            'td_id' => $new_tr
        ], [
            'table_id' => $this->table_id,
            'td_id' => $td_id
        ]);

        // достаем из буфера
        TableCells::updateAll([
            'td_id' => $td_id
        ],
            [
                'table_id' => $this->table_id,
                'td_id' => 0
            ]);


    }


    public function reset()
    {

        if ($rows = TableCells::find()->where(['table_id' => $this->table_id])->orderBy('tr_id')->distinct()->select('tr_id')->column()) {
            //   D::dump($rows);
            foreach ($rows as $key => $row) {
                //   D::dump($row);
                // D::success("SETTING NEW TR_ID ".($key+1)." TO ".$row);
                TableCells::updateAll(['tr_id' => $key + 1], ['tr_id' => $row, 'table_id' => $this->table_id]);
                TableRows::updateAll(['tr_id' => $key + 1], ['tr_id' => $row, 'table_id' => $this->table_id]);
            }
        }
        if ($columns = TableCells::find()->where(['table_id' => $this->table_id])->orderBy('td_id')->distinct()->select('td_id')->column()) {
            //   D::dump($rows);
            foreach ($columns as $key => $column) {
                //   D::dump($row);
                // D::success("SETTING NEW TR_ID ".($key+1)." TO ".$row);
                TableCells::updateAll(['td_id' => $key + 1], ['td_id' => $column, 'table_id' => $this->table_id]);
                TableColumns::updateAll(['td_id' => $key + 1], ['td_id' => $column, 'table_id' => $this->table_id]);

            }
        }

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

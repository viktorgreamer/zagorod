<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int $time
 * @property int $smeta_id
 * @property int $type
 * @property string $link
 *
 * @property Smeta $smeta
 */
class Files extends \yii\db\ActiveRecord
{
    const TYPE_EXCEL = 1;
    const TYPE_PDF = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'smeta_id', 'type'], 'integer'],
            [['smeta_id', 'type', 'link'], 'required'],
            [['link'], 'string', 'max' => 255],
            [['smeta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Smeta::className(), 'targetAttribute' => ['smeta_id' => 'smeta_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'smeta_id' => 'Smeta ID',
            'type' => 'Type',
            'link' => 'Link',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmeta()
    {
        return $this->hasOne(Smeta::className(), ['smeta_id' => 'smeta_id']);
    }
}

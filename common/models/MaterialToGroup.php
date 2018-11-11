<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "material_to_group".
 *
 * @property int $id
 * @property int $material_id
 * @property int $group_id
 */
class MaterialToGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material_to_group';
    }

    public function getMaterial() {
        return $this->hasOne(Material::className(),['articul' => 'articul']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['articul', 'group_id'], 'required'],
            [['group_id','count'], 'integer'],
            [['articul'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'articul' => 'Материал',
            'group_id' => 'Группа',
            'count' => 'Количество',
        ];
    }
}

<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_station_group".
 *
 * @property int $group_id id
 * @property string $name Название группы
 * @property int $description
 */
class BaseStationGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_station_group';
    }

    public function getMaterials()
    {
        return $this->hasMany(MaterialToGroup::className(), ['group_id' => 'group_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['group_id'],'integer'],
            [['name','description'], 'string', 'max' => 256],
        ];
    }

    public static function map()
    {
        return ArrayHelper::map(BaseStationGroup::find()->all(),'group_id' ,'name');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'id',
            'name' => 'Название группы',
            'description' => 'Описание',
        ];
    }
}

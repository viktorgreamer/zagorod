<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regions".
 *
 * @property int $id id
 * @property string $name Название
 * @property string $tc Коэф. утепления
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tc'], 'required'],
            [['tc'], 'number'],
            [['name','phone','email','site','site2'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Название',
            'tc' => 'Коэф. утепления',
            'phone' => 'Телефон',
            'site' => 'Сайт',
            'site2' => 'Доп. сайт.',
            'email' => 'Email',
        ];
    }
}

<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "estimate".
 *
 * @property int $estimate_id
 * @property string $name
 * @property int $date
 */
class Estimate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estimate';
    }

    public function getParent()
    {
        return $this->hasOne(Estimate::className(), ['estimate_id' => 'parent_id']);
    }


    public function getChilds()
    {
        return $this->hasMany(Estimate::className(), ['parent_id' => 'estimate_id']);
    }

    public function mapParents()
    {
        return ArrayHelper::map(Estimate::find()->all(), 'estimate_id', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['estimate_id', 'date', 'parent_id'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function debugSave()
    {
        if (!$this->save()) {
            $keys = array_keys($this->getErrors());
            foreach ($keys as $key) {
                D::alert($key . "=" . $this->$key);
            }
            D::dump($this->getErrors());
        }
        // $this->save();
    }


    public function getStages()
    {
        return $this->hasMany(EstimateStage::className(), ['estimate_id' => 'estimate_id'])->andWhere(['type' => EstimateStage::TYPE_INPUT])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->orderBy('priority');
    }
    public function getStagesInput()
    {
        return $this->hasMany(EstimateStage::className(), ['estimate_id' => 'estimate_id'])->andWhere(['inInput' => EstimateStage::STATUS_ACTIVE])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->orderBy('priority');
    }

    public function getStagesOutput()
    {
        return $this->hasMany(EstimateStage::className(), ['estimate_id' => 'estimate_id'])->andWhere(['inOutput' => EstimateStage::STATUS_ACTIVE])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->orderBy('priority');
    }

    public function getOutputs()
    {
        return $this->hasMany(Output::className(), ['estimate_id' => 'estimate_id']);
    }
    public function getInputs()
    {
        return $this->hasMany(Input::className(), ['estimate_id' => 'estimate_id']);
    }




    public function getInputValues()
    {
        return $this->hasMany(InputValue::className(), ['estimate_id' => 'estimate_id']);
    }

    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['estimate_id' => 'estimate_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'estimate_id' => 'Смета',
            'name' => 'Наименование',
            'parent.name' => 'Входит в',
            'date' => 'Дата создания',
        ];
    }
}

<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "smeta".
 *
 * @property int $smeta_id id
 * @property int $estimate_id
 * @property int $parent_id
 * @property int $date
 * @property string $name
 */
class Smeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smeta';
    }


    public function mapEstimates()
    {
        return ArrayHelper::map(Estimate::find()->all(), 'estimate_id', 'name');
    }

    public function beforeSave($insert)
    {

        return parent::beforeSave($insert);
    }

    public function beforeValidate()
    {

        if ($_POST) {
            $estimates = array_filter($_POST, function ($key) {
                return preg_match("/estimate_(\d{1,4})/", $key);
            }, ARRAY_FILTER_USE_KEY);
            $estimatesPlus = array_filter($estimates, function ($item) {
                return in_array($item, ['1', true, 1]);
            });
            EstimatesToSmeta::updateAll(['status' => 0], ['smeta_id' => $this->smeta_id]);
            $estimatesMinus = array_diff($estimates, $estimatesPlus);
            if ($estimates) {

                foreach ($estimates as $key => $estimate) {
                    preg_match("/estimate_(\d{1,4})/", $key, $matches);

                    if ($this->smeta_id) {
                        if ($existed = EstimatesToSmeta::find()->where(['smeta_id' => $this->smeta_id])->andWhere(['estimate_id' => $matches[1]])->one()) {
                            if ($existed->status != 1) {
                                $existed->status = 1;
                                $existed->save();

                            }
                        } else {

                            $new_estimateToSmeta = new EstimatesToSmeta(['smeta_id' => $this->smeta_id, 'estimate_id' => $matches[1], 'status' => 1]);

                            if (!$new_estimateToSmeta->save()) D::dump($new_estimateToSmeta->errors);
                        }

                    }
                }
            }
        }

        if (!$this->date) $this->date = time();
        if (!isset($this->user_id)) $this->user_id = Yii::$app->user->identity->getId();
        return parent::beforeValidate();
    }

    public function getEstimates()
    {
        return $this->hasMany(Estimate::className(), ['estimate_id' => 'estimate_id'])
            ->viaTable(EstimatesToSmeta::tableName(), ['smeta_id' => 'smeta_id']);
    }

    public function getEstimatesId()
    {
        return $this->hasMany(EstimatesToSmeta::className(), ['smeta_id' => 'smeta_id'])->select('estimate_id')->orderBy('priority')->andWhere(['status' => 1])->column();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'user_id'], 'required'],
            [['estimate_id'], 'integer'],
            [['name'], 'string'],
            [['name'], 'safe'],
            [['date'], 'integer'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getEstimate()
    {
        return $this->hasOne(Estimate::className(), ['estimate_id' => 'estimate_id']);
    }

    public function calcutale()
    {
        $estimate = $this->estimate;
        // D::success(" ESTIMATE_ID_" . $estimate->estimate_id);

        if ($inputsValues = $estimate->inputValues) {
            foreach ($inputsValues as $inputsValue) {
                $inputsData["input\[" . $inputsValue->input_id . "\]"] = $inputsValue->value;

            }
        };

        //  D::dump($inputsData);
        if ($outputs = $estimate->outputs) {
            foreach ($outputs as $output) {
                $formula = $output->formula;
                foreach ($inputsData as $key => $inputsDatum) {
                    //   D::success("/".$key."/ ->>>".$inputsDatum." ->>> ".$formula);
                    $formula = preg_replace("/" . $key . "/", $inputsDatum, $formula);
                }

                if ($output->result) {
                    $value = eval($formula . "; return " . $output->result . ";");
                } else {
                    $value = eval("return " . $formula . ";");
                }

                //  D::success("FORUMULA=  " . $formula);
                //  D::success("VALUE =  " . $value);
                if ($output_value = OutputValue::find()->where(['smeta_id' => $this->smeta_id])->andWhere(['output_id' => $output->output_id])->one()) {
                } else {
                    $output_value = new OutputValue(['smeta_id' => $this->smeta_id, 'output_id' => $output->output_id]);

                }
                $output_value->value = strval($value);
                if (!$output_value->save()) D::dump($output_value->getErrors());


            }
        };
        //   D::dump($outputs);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'smeta_id' => 'id',
            'user_id' => 'Мастер',
            'estimate_id' => 'Estimate ID',
            'date' => 'Date',
            'estimate.name' => 'Из',
            'name' => 'Название',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SmetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SmetaQuery(get_called_class());
    }
}

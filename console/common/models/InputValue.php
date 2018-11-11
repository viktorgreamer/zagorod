<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "input_value".
 *
 * @property int $id
 * @property int $input_id
 * @property int $smeta_id
 * @property int $value
 * @property int $estimate_id
 */
class InputValue extends \yii\db\ActiveRecord
{


    public function validateValue()
    {
        $return = [];

        $rule_id = Input::findOne($this->input_id)->validation_rule_id;
        //  $return .= 'TRY TO VALIDATE VALUE = ' . $this->value . ' BY RULE_ID = ' . $rule_id;
        if ($validationRule = ValidationRule::findOne($rule_id)) {
            if ($validationRule->check_value($this->value) === true) {

            } else {
                $return = ['error' => $validationRule->renderRule()];
            }
        }

        return $return;
    }

    public static function set($smeta, $input, $value)
    {


        if ($inputValue = InputValue::find()->where(['smeta_id' => $smeta->smeta_id])->andWhere(['input_id' => $input->input_id])->one()) {
            //  $return .= " INPUT_ID_" . $input->input_id . " IS EXISTS";
            $inputValue->value = $value;
            $return = $inputValue->validateValue();
        } else {
            $inputValue = new InputValue();
            $inputValue->smeta_id = $smeta->smeta_id;
            $inputValue->estimate_id = $smeta->estimate_id;
            $inputValue->input_id = $input->input_id;
            $inputValue->value = $value;
            $return = $inputValue->validateValue();
        }
        if (!$return['error']) {
            if (!$inputValue->save()) $return = $inputValue->getErrors();
        }

        return $return;

    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'input_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['input_id', 'smeta_id', 'value', 'estimate_id'], 'required'],
            [['input_id', 'smeta_id', 'estimate_id'], 'integer'],
            [['value'], 'string'],
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
            'smeta_id' => 'Smeta ID',
            'value' => 'Value',
            'estimate_id' => 'Estimate ID',
        ];
    }
}

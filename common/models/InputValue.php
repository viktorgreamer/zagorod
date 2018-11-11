<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "input_value".
 *
 * @property int $id
 * @property int $input_id
 * @property int $smeta_id
 * @property int $value
 * @property int $estimate_id
 * @property int $type
 */
class InputValue extends \yii\db\ActiveRecord
{


    public function validateValue()
    {
        $return = [];

        $input = Input::findOne($this->input_id);
        $rule_id = $input->validation_rule_id;
        // D::alert('TRY TO VALIDATE VALUE = ' . $this->value . ' BY RULE_ID = ' . $rule_id);
        if ($validationRule = ValidationRule::findOne($rule_id)) {
        } else {
            $validationRule = new ValidationRule();
        }

        // наследуем обязательное поле для того чтобы можно было модифицировать на стадии добавления поля
        $validationRule->required = $input->required;
        $response = $validationRule->check($this->value);
        if ($response === true) {
            //   D::alert('VALUDATION_SUCCESSFULL');
        } else {
            //  D::alert('VALUDATION_ERROR');
            $return = ['error' => $validationRule->renderRule($response)];
        }


        return $return;
    }

    public function getInput()
    {
        return $this->hasOne(Input::className(), ['input_id' => 'input_id']);
    }

    public static function set($smeta, $input, $value, $multiple = false)
    {
        /* @var $inputValue InputValue */
        /* @var $input Input */

        $inputValue = new InputValue();
        $inputValue->smeta_id = $smeta->smeta_id;
        $inputValue->estimate_id = $smeta->estimate_id;
        $inputValue->input_id = $input->input_id;
        $inputValue->type = $input->type;
        if (is_array($value)) {
            // D::success(" VALUE OF INPUT $input->input_id IS ARRAY ");

            if ($input->type == Input::GROUP_INPUT) {
                //   D::success(" COLUMNS SCHEMA IS ");
                if ($schemas = $input->getColumnsSchema()) {
                    foreach ($schemas as $schema) {
                        if ($schema['rule']) $rules[$schema['name']] = $schema['rule'];
                    }
                };
                //  D::success("RULES");
                D::dump($rules);
                foreach ($value as $row) {

                    if (is_array($row)) {
                        foreach ($row as $key_name => $item) {
                            $return = [];
                            $validate = true;
                            D::success("NAME =  $key_name");
                            if ($rules[$key_name]) {
                                //   D::success(" ITEM HAS RULE");
                                $rule = ValidationRule::findOne($rules[$key_name]);

                                $response = $rule->check($item);
                                if ($response === true) {
                                    //   D::alert('VALUDATION_SUCCESSFULL');
                                } else {
                                    //  D::alert('VALUDATION_ERROR');
                                    $return = ['error' => $rule->renderRule($response)];
                                }
                                if (!$return['error']) {
                                    if (!$inputValue->save()) D::dump($inputValue->getErrors());
                                } else {
                                    $validate = false;
                                    break;

                                }

                            }
                        }
                        if ($validate) {
                            $inputValue->value = serialize($value);
                            if (!$inputValue->save()) D::dump($inputValue->getErrors());
                        } else {
                            break;
                            //  D::success(" VALIDATION OF ARRAY IS ERROR");
                        }
                    }
                }

            } else {
                $validate = true;
                foreach ($value as $item) {
                    $inputValue->value = $item;
                    $return = $inputValue->validateValue();
                    if ($return['error']) {
                        $validate = false;
                        break;
                    }
                }
                if ($validate) {
                    $inputValue->value = serialize($value);
                    if (!$inputValue->save()) D::dump($inputValue->getErrors());
                } else {
                    //  D::success(" VALIDATION OF ARRAY IS ERROR");
                }

            }


        } else {
            //  D::success(" VALUE OF INPUT $input->input_id IS NOT ARRAY ");
            $inputValue->value = $value;
            $return = $inputValue->validateValue();
            if (!$return['error']) {
                if (!$inputValue->save()) D::dump($inputValue->getErrors());
            }
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
            [['input_id', 'smeta_id', 'estimate_id'], 'required'],
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
            'type' => 'Тип',
        ];
    }
}

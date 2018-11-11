<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "output".
 *
 * @property int $output_id Данные
 * @property string $name
 * @property string $formula Формула
 * @property string $variables Переменные в формуле
 * @property int $estimate_id Смета
 * @property int $stage_id Этап
 * @property int $width
 * @property int $priority
 * @property int $type Тип
 * @property string $result результат
 */
class Output extends Input
{

    const STRING_REPLACE = 1;
    const CALCULATION = 2;


    public function mapTypes()
    {
        return [
            self::STRING_REPLACE => "Замена строки",
            self::CALCULATION => "Числовое вычисление",
        ];
    }

    public function renderFormula($variables)
    {

        $old_result = $this->result;
        $old_formula = $this->formula;

        foreach ($variables as $key => $inputsDatum) {
            //  D::success($inputsDatum);
            if (preg_match('/\{.+\}/', $inputsDatum)) {
                //  D::alert($inputsDatum . " IS ARRAY ");
                //  $inputsDatum = "'".$inputsDatum."'";
                // continue
                $formula = preg_replace("/" . $key . "/", "unserialize(" . $inputsDatum . ")", $old_formula);
                $result = preg_replace("/" . $key . "/", "unserialize(" . $inputsDatum . ")", $old_result);


            } else {
                $formula = preg_replace("/" . $key . "/", $inputsDatum, $old_formula);
                $result = preg_replace("/" . $key . "/", $inputsDatum, $old_result);

            }

            $old_formula = $formula;
            $old_result = $result;


        }


        if (($result != $this->result) AND ($formula != $this->formula)) {
            D::success("/" . $key . "/ ->>>" . $inputsDatum . " ->>> " . $result);
            $no_replace = false;

        } else $no_replace = true;


        /* if ($_REQUEST['debug_formula']) {
             if ($this->checkFormula($formula . "; echo " . $result . ";") === true) {
                 D::success(" SUCCESS CHECKING FORMULA");
                 $this->formula = $formula;
                 $this->result = $result;
                 return true;
             } else return false;
         }*/

        $this->formula = $formula;
        $this->result = $result;
        return $no_replace;

    }

    public function checkFormula($formula)
    {

        $formula = urlencode($formula);


        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://phpcodechecker.com/api/?code=$formula;",
            // CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);


        //  $response = file_get_contents();

        $response = json_decode(gzdecode($response));
        if ($response->errors == 'TRUE') {
            if ($message = $response->syntax->message) {
                $message = preg_replace("/Parse error: syntax error, unexpected/", "Синтактическая ошибка, не ожидается ", $message);
                $message = preg_replace("/ in your code on line/", " в формуле в линии ", $message);
                D::alert($message);

                Yii::$app->session->set('error_message', $message);
                Yii::$app->session->set('code', $response->code);
            }
            D::dump($response);
            D::alert(" ERRORS");
            return false;
        } else {
            Yii::$app->session->set('error_message', '');
            Yii::$app->session->set('code', '');
            D::dump($response);
            return true;
        }


    }


    public function evaluate($variables)
    {
        if ($this->renderFormula($variables) === false) $type = 'warning';

        if (trim($this->formula)) {
            $eval = $this->formula . "; return " . $this->result . ";";
            D::success("EVAL  = " . $eval);
            try {
                $value = eval($eval);
                $type = 'success';

            } catch (\ParseError $e) {
                $type = 'danger';
                $value = '';
                $message = $e->getMessage() . " in " . $e->getCode() . " at line " . $e->getLine();
                Yii::$app->session->set("error", $e->getMessage());

            }

        } else {
            $eval = "return " . $this->result . ";";
            D::success("EVAL  = " . $eval);

            try {
                $type = 'success';
                $value = eval($eval);

            } catch (\ParseError $e) {
                $type = 'danger';
                $message = $e->getMessage() . " in " . $e->getCode() . " at line " . $e->getLine();
                D::dump("ERROR MESSAGE  = " . $e->getMessage());
            }
        }

        return ['type' => $type, 'message' => $message, 'eval' => $eval, 'value' => $value];


    }

    public static $formulaName = 'output_{id}_';

    public function getFormulaName()
    {
        return preg_replace("/{id}/", $this->output_id, self::$formulaName);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'output';
    }

    public function getInputs()
    {
        return $this->hasMany(Input::className(), ['input_id' => 'input_id'])
            ->viaTable(InputToOutput::tableName(), ['output_id' => 'output_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estimate_id', 'stage_id'], 'required'],
            [['estimate_id', 'stage_id', 'width', 'priority', 'type'], 'integer'],
            [['name', 'formula', 'result'], 'string', 'max' => 256],
            [['variables'], 'string'],
        ];
    }

    public function reorderPriority($priority)
    {
        if ($priority == 'up') {
            if ($this->priority != 1) {
                $this->priority = $this->priority - 1;
                $input_down = Output::find()->where(['stage_id' => $this->stage_id])->andWhere(['priority' => $this->priority])->one();
                $input_down->priority = $input_down->priority + 1;

                $input_down->save();
            }

        } elseif ($priority == 'down') {
            $max = Input::find()->where(['stage_id' => $this->stage_id])->max('priority');
            if ($this->priority != $max) {
                $this->priority = $this->priority + 1;
                $input_up = Output::find()->where(['stage_id' => $this->stage_id])->andWhere(['priority' => $this->priority])->one();
                $input_up->priority = $input_up->priority - 1;
                $input_up->save();
            }

        }
        $this->save();


    }

    public function beforeValidate()
    {

        if (!$this->estimate_id) {
            $stage = EstimateStage::findOne($this->stage_id);
            $this->estimate_id = $stage->estimate_id;
        }
        return parent::beforeValidate();

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


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'output_id' => 'Данные',
            'name' => 'Название',
            'formula' => 'Формула',
            'estimate_id' => 'Смета',
            'stage_id' => 'Этап',
            'width' => 'Ширина',
            'priority' => 'Приоритет',
            'result' => 'Результат',
            'variables' => 'Переменные в формуле',
            'type' => 'Тип данных',
        ];
    }
}

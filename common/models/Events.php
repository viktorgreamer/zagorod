<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "events".
 *
 * @property int $event_id
 * @property string $name Название
 * @property int $estimate_id Смета
 * @property string $formula Формула
 * @property string $variables Переменные в формуле
 * @property int $type Тип
 * @property string $result Результат
 * @property int $parent_id В
 */
class Events extends \yii\db\ActiveRecord
{
    private $value = false;

    public static $formulaName = 'event_{id}_';
    public static $pattern = '/event_(\d+)_/';

    public function getFormulaName()
    {
        return preg_replace("/{id}/", $this->event_id, self::$formulaName);
    }

    public function getFormulaLink()
    {
        return $this->getFormulaName() . " =" . $this->estimate->name . "->" . $this->name;
    }



    public function getExtendedName() {
        return "- ".$this->getFormulaName()." - ".$this->estimate->name." - ".$this->name;
    }

    const TRUE = 1;
    const FALSE = 0;


    const TYPE_EQUALS = 1;
    const TYPE_NOT_EQUALS = 2;
    const TYPE_MORE = 3;
    const TYPE_MORE_OR_EQUALS = 4;
    const TYPE_LESS = 5;
    const TYPE_LESS_OR_EQUALS = 6;
    const TYPE_MATCHES = 7;
    const TYPE_AND = 8;
    const TYPE_OR = 9;
    const TYPE_TRUE = 10;
    const TYPE_FALSE = 11;
    const TYPE_IN_ARRAY = 12;
    const TYPE_NOT_IN_ARRAY = 13;

   static $positive = [1,3,4,10,12];
   static $negative = [2,5,6,11,13];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    public static function mapTypes()
    {
        return [
            self::TYPE_EQUALS => '=',
            self::TYPE_NOT_EQUALS => '!=',
            self::TYPE_MORE => '>',
            self::TYPE_MORE_OR_EQUALS => '>=',
            self::TYPE_LESS => '<',
            self::TYPE_LESS_OR_EQUALS => '<=',
            self::TYPE_MATCHES => 'match',
            self::TYPE_AND => 'AND',
            self::TYPE_OR => 'OR',
            self::TYPE_TRUE => 'TRUE',
            self::TYPE_FALSE => 'FALSE',
            self::TYPE_IN_ARRAY => 'В списке',
            self::TYPE_NOT_IN_ARRAY => 'Не в списке',
        ];
    }

    public static function mapEvents()
    {
        return ArrayHelper::map(Events::find()->all(), 'event_id', 'name');
    }

    public static function mapEstimates()
    {
        return ArrayHelper::map(Estimate::find()->all(), 'estimate_id', 'name');
    }

    public function getStages()
    {
        return $this->hasMany(EstimateStage::className(), ['event_id' => 'event_id']);
    }

    public function getInfluence()
    {
        $names = [];
        if ($stages = $this->stages) {

            foreach ($stages as $stage) {
                $names[] = $stage->name;
            }

        }

        if ($inputs = $this->inputs) {

            foreach ($inputs as $input) {
                $names[] = $input->name;
            }

        }

        return implode("<br>", $names);

    }

    public function getTypeText()
    {
        return self::mapTypes()[$this->type];
    }

    public function getEstimate()
    {
        return $this->hasOne(Estimate::className(), ['estimate_id' => 'estimate_id']);
    }

    public function getInputs()
    {
        return $this->hasMany(Input::className(), ['event_id' => 'event_id']);
    }


    public function registerResultsTrue()
    {

        $inputs = $this->getInputs()->asArray()->select('event_id')->all();
        $stages = $this->getStages()->asArray()->select('event_id')->all();
        //  D::dump($inputs);
        // D::dump($stages);
        $union = array_merge($inputs, $stages);
        $union = array_unique($union);
        if ($union) {
            $results = [];
            foreach ($union as $event) {
                //  $results[] = "document.getElementById('stage_id_" . $stage->stage_id . "').style.display = 'none'";
                // $results[] = "nodelists = document.querySelectorAll(\"[data-event_id='$this->event_id']\");
                $results[] = "nodelists = event_nodes($this->event_id); \r\n
              
                nodelists.forEach( function(item) {
             // console.log(item.tagName);
               item.classList.add('active_element');
                  item.classList.remove('hidden');
               
                 });";
                $results[] = "item = findEventById(" . $this->event_id . ",events); item[0].value=1";
            }

            $results = implode(";", $results);
            return $results;
        } else return '';
    }

    public function registerResultsFalse()
    {
        $inputs = $this->getInputs()->asArray()->select('event_id')->all();
        $stages = $this->getStages()->asArray()->select('event_id')->all();
        //  D::dump($inputs);
        // D::dump($stages);
        $union = array_merge($inputs, $stages);
        $union = array_unique($union);
        if ($union) {
            $results = [];
            foreach ($union as $event) {
                //    $results[] = "document.getElementById('stage_id_" . $stage->stage_id . "').style.display = ''";
                 // $results[] = "nodelists = document.querySelectorAll(\"[data-event_id='$this->event_id']\");
                $results[] = "nodelists = event_nodes($this->event_id); \r\n
                nodelists.forEach( function(item) {
                 console.log(item.tagName);
                   item.classList.remove('active_element');
                  item.classList.add('hidden');
                  
           
                           });";
                $results[] = "item = findEventById(" . $this->event_id . ",events); item[0].value=0";
            }

            $results = implode(";", $results);
            return $results;
        } else return '';
    }

    public function renderFormula($smeta)
    {

        $station = Yii::$app->session->get('station');

        /* @var $smeta Smeta */

        $variableValues = $smeta->loadVariables();
        //  D::dump($variableValues);
        $formula = $this->formula;

        foreach ($variableValues as $variableName => $variableValue) {
            $formula = str_replace($variableName, $variableValue, $formula);
        }



        return $formula;


    }

    public function renderFormulaJS($smeta)
    {
        /* @var $smeta Smeta */

        $variableValues = $smeta->loadVariables();
        //  D::dump($variableValues);
        $formula = $this->formula;

        foreach ($variableValues as $variableName => $variableValue) {
         // if (preg_match("/input/",$variableName))  $formula = str_replace($variableName, "get_input_value('" . $variableName . "')", $formula);
         // if (preg_match("/input/",$variableName))  $formula = str_replace($variableName, "get_input_value('" . $variableName . "')", $formula);


         // elseif (preg_match("/station/",$variableName)) $formula = str_replace($variableName, $variableValue, $formula);
        }

        return $formula;


    }


    public function check($smeta)
    {

          if (preg_match('/[a-zа-яA-ZА-Я]/u', $this->result)) {
              D::success("RESULT IS STRING");
              $result = "'" . $this->result . "'";
          } else {
              D::success("RESULT IS NOT STRING");
              $result = $this->result;
          }


        $station = Yii::$app->session->get('station');
      //  D::dump($station);
        $value = self::FALSE;
        switch ($this->type) {
            case self::TYPE_TRUE:
                {
                    $formula = $this->renderFormula($smeta);
                    D::alert(" FORMULA = (" . $formula.")");
                    $result = eval('return ' . $formula . ";");

                    if ($result)
                        $value = self::TRUE;

                    break;

                }

            case self::TYPE_FALSE:
                {
                    $formula = $this->renderFormula($smeta);
                    D::alert(" FORMULA = " . $formula);

                    $result = eval('return ' . $formula . ";");

                    if (!($result))
                        $value = self::TRUE;

                    break;

                }
            case self::TYPE_EQUALS:
                {
                    $formula = $this->renderFormula($smeta);
                  //  $result = $this->result;
                  //  if (preg_match('/[a-zа-я]/u', $this->result)) $result = "'" . $this->result . "'"; else $result = $this->result;
                  //  if (preg_match('/[a-zа-я]/u', $formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " == " . $result;
                    D::alert('return (' . $statement . ");");


                    $result = eval('return ' . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }


            case self::TYPE_NOT_EQUALS:
                {
                    $formula = $this->renderFormula($smeta);
                   // $result = $this->result;
                  //  if (preg_match('/[a-zа-я]/u', $this->result)) $result = "'" . $this->result . "'"; else $result = $this->result;
                  //  if (preg_match('/[a-zа-я]/u', $formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " != " . $result;
                    D::alert("return (" . $statement . ");");

                    $result = eval("return " . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }

            case self::TYPE_MORE:
                {
                    $formula = $this->renderFormula($smeta);
                    //if (is_string($this->result)) $result = "'" . $this->result . "'";
                   // if (is_string($formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " > " . $result;
                    D::alert('return (' . $statement . ");");

                    $result = eval('return ' . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }

            case self::TYPE_MORE_OR_EQUALS:
                {
                    $formula = $this->renderFormula($smeta);
                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    if (is_string($formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " >= " . $result;
                    D::alert('return (' . $statement . ");");

                    $result = eval('return ' . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }
            case self::TYPE_LESS:
                {
                    $formula = $this->renderFormula($smeta);
                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    if (is_string($formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " < " . $result;
                    D::alert('return (' . $statement . ");");

                    $result = eval('return ' . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }
            case self::TYPE_LESS_OR_EQUALS:
                {
                    $formula = $this->renderFormula($smeta);
                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    if (is_string($formula)) $formula = "'" . $formula . "'";

                    $statement = $formula . " <= " . $result;
                    D::alert('return (' . $statement . ");");

                    $result = eval('return ' . $statement . ";");


                    if ($result) $value = self::TRUE;

                    break;

                }

            case self::TYPE_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = "[" . $items . "]";
                    } else {
                        $array = "[" . $result . "]";
                    }

                    $formula = $this->renderFormula($smeta);
                    D::alert(" FORMULA = " . $formula . " RESULT = " . $result);
                    $statement = "in_array(" . $formula . "," . $array . ")";

                    if (eval('return ' . $statement . ";")) $value = self::TRUE;

                    break;

                }

            case self::TYPE_NOT_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = "[" . $items . "]";
                    } else {
                        $array = "[" . $result . "]";
                    }

                    $formula = $this->renderFormula($smeta);
                    D::alert(" FORMULA = " . $formula . " RESULT = " . $result);
                    $statement = "(!in_array(" . $formula . "," . $array . "))";

                    if (eval('return ' . $statement . ";")) $value = self::TRUE;

                    break;

                }

        }
        $this->register($smeta->smeta_id, $value);
        D::alert(" VALUE = " . $value);
        return $value;
    }

    public function register($smeta_id, $value)
    {
        if ($isExistedSmetaEvent = SmetaEvents::find()->where(['smeta_id' => $smeta_id])->andWhere(['event_id' => $this->event_id])->one()) {
            $isExistedSmetaEvent->value = $value;
            if (!$isExistedSmetaEvent->save()) {
                D::dump($isExistedSmetaEvent->errors);
                file_put_contents('errors.json', json_encode($isExistedSmetaEvent->errors));
            }
        } else {
            $SmetaEvent = new SmetaEvents(['smeta_id' => $smeta_id, 'event_id' => $this->event_id]);
            $SmetaEvent->value = $value;
            if (!$SmetaEvent->save()) {
                D::dump($SmetaEvent->errors);
                file_put_contents('errors.json', json_encode($SmetaEvent->errors));
            }
        }
    }

    public function renderListeners($smeta)
    {
        switch ($this->type) {
            case self::TYPE_TRUE:
                {
                    $listener_body = " \r\n if (isChecked('" . $this->formula . "')) {
                   //  console.log('INPUT WAS CHECKED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
              //  console.log(item);
           
        }";
                    break;
                }
            case self::TYPE_FALSE:
                {
                    $listener_body = " \r\n if (isChecked('" . $this->formula . "')) {
                    // console.log('INPUT WAS CHECKED');
                   
           
          " . $this->registerResultsFalse() . "
            // console.log(item);
        } else {
        // console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsTrue() . "
                console.log(item);
           
        }";
                    break;
                }

            case self::TYPE_EQUALS:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") == " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }
            case self::TYPE_NOT_EQUALS:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") != " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

            case self::TYPE_MORE:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") > " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }
            case self::TYPE_MORE_OR_EQUALS:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") >= " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

            case self::TYPE_LESS:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") < " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

            case self::TYPE_LESS_OR_EQUALS:
                {

                    if (is_string($this->result)) $result = "'" . $this->result . "'";
                    $listener_body = " \r\n if ((" . $this->renderFormulaJS($smeta) . ") < " . $result . " ) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

            case self::TYPE_NOT_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = " array_event_" . $this->event_id . " =[" . $items . "];";
                    }

                    $listener_body = "$array \r\n if (array_event_" . $this->event_id . ".indexOf(" . $this->renderFormulaJS($smeta) . ") == -1) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }
            case self::TYPE_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = " array_event_" . $this->event_id . " =[" . $items . "];";
                    }

                    $listener_body = "$array \r\n if (array_event_" . $this->event_id . ".indexOf(" . $this->renderFormulaJS($smeta) . ") != -1) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

        }

        return $listener_body;
    }
    public function renderFormulaForJSEvents($smeta)
    {
        switch ($this->type) {
            case self::TYPE_TRUE:
                {
                    $listener_body = trim($this->formula);
                    break;
                }
            case self::TYPE_FALSE:
                {
                    $listener_body = "(!" . trim($this->formula) . ")";

                    break;
                }

            case self::TYPE_EQUALS:
                {

                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " == " . $result . ")";
                    break;
                }
            case self::TYPE_NOT_EQUALS:
                {

                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " != " . $result . ")";
                    break;

                }

            case self::TYPE_MORE:
                {


                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " > " . $result . ")";
                    break;
                }
            case self::TYPE_MORE_OR_EQUALS:
                {


                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " >= " . $result . ")";
                    break;
                    break;
                }

            case self::TYPE_LESS:
                {


                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " < " . $result . ")";
                    break;
                    break;
                }

            case self::TYPE_LESS_OR_EQUALS:
                {


                    if (!filter_var($this->result, FILTER_VALIDATE_FLOAT)) $result = "'" . $this->result . "'";
                    else $result = $this->result;
                    $listener_body = "(" . $this->renderFormulaJS($smeta) . " <= " . $result . ")";
                    break;
                }

            case self::TYPE_NOT_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = " array_event_" . $this->event_id . " =[" . $items . "];";
                    }

                    $listener_body = "$array \r\n if (array_event_" . $this->event_id . ".indexOf(" . $this->renderFormulaJS($smeta) . ") == -1) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }
            case self::TYPE_IN_ARRAY:
                {

                    $result = $this->result;
                    if (preg_match("/,/", $result)) {
                        $items = [];
                        foreach (explode(",", $result) as $item) {
                            if (is_string($item)) $items[] = "'" . $item . "'";
                        }
                        $items = implode(",", $items);
                        $array = " array_event_" . $this->event_id . " =[" . $items . "];";
                    }

                    $listener_body = "$array \r\n if (array_event_" . $this->event_id . ".indexOf(" . $this->renderFormulaJS($smeta) . ") != -1) {
                     console.log('INPUT WAS SELECTED');
                   
           
          " . $this->registerResultsTrue() . "
            // console.log(item);
        } else {
         console.log('INPUT WAS NOT CHECKED');
            
            " . $this->registerResultsFalse() . "
                console.log(item);
           
        }";
                    break;
                }

        }

        return $listener_body;
    }


    public function renderJs($smeta)
    {
        $listener_open = "$(document).on('change', '.input_field',function () {";

        $listener_body = $this->renderListeners($smeta);
        $listener_close = "
       //  console.log(events);
         });";

        return $listener_open . $listener_body . $listener_close;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'formula', 'estimate_id', 'type'], 'required'],
            [['type', 'parent_id'], 'integer'],
            [['name', 'formula', 'result'], 'string', 'max' => 256],
            [['variables'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'ID',
            'estimate.name' => 'Шаблон сметы',
            'estimate_id' => 'Шаблон сметы',
            'name' => 'Название',
            'formula' => 'Формула',
            'type' => 'Тип',
            'result' => 'Результат',
            'parent_id' => 'Относится к',
            'influence' => 'Влияет на',
            'variables' => 'Переменные в формуле',
        ];
    }
}

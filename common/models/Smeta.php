<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * This is the model class for table "smeta".
 *
 * @property int $smeta_id id
 * @property int $estimate_id
 * @property int $parent_id
 * @property int $current_stage
 * @property int $created_at
 * @property int $updated_at
 * @property int $history_of;
 * @property string $name
 */
class Smeta extends \yii\db\ActiveRecord
{
    protected $variables;
    protected $variablesKeys;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smeta';
    }

    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['smeta_id' => 'smeta_id']);
    }

    public function getDates()
    {
        return "Создана " . Yii::$app->formatter->asDatetime($this->created_at) . "<br>Обновлена " . Yii::$app->formatter->asDatetime($this->updated_at);
    }

    public function reportExcel($table)
    {
        /* @var $table Table */
        $filename = "smeta_" . $this->smeta_id . "_" . date("d.m.y h.i.s A") . ".xlsx";
        $excelTable = new ExcelTable();
        $excelTable->table_id = $table->table_id;
        $excelTable->make($this);
        if ($excelTable->saveToExcel($filename)) {
            $file = new Files(['type' => Files::TYPE_EXCEL,'smeta_id' => $this->smeta_id,'link' => "/export/".$filename,'time' => time()]);
          D::dump($file);
           if (!$file->save()) D::dump($file->errors);
            return $filename;
        }
        else return false;

    }

    public function renderFiles() {
        if ($files = $this->files) {
            foreach ($files as $file) {
                if ($file->type == Files::TYPE_EXCEL) {
                    $excels[] = "<br>".Html::a(basename($file->link),$file->link,['target' => 'blank']);
                } else {
                    $pdfs[] = "<br>".Html::a(basename($file->link),$file->link,['target' => 'blank']);
                }
            }
            if ($excels) $body[] = implode(" ",$excels);
            if ($pdfs) $body[] = implode(" ", $pdfs);
            return implode("<br>",$body);
        }
    }

    public function getCopies()
    {
        return $this->hasMany(self::className(), ['history_of' => 'smeta_id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['smeta_id' => 'history_of']);
    }

    public function renderCopies()
    {
        /* @var $copy Smeta */
        if ($copies = $this->copies) {
            $li = [];
            foreach ($copies as $copy) {
                $li[] = Html::tag('li', Html::a($copy->name, ['smeta/view', 'id' => $copy->smeta_id], ['target' => '_blank']), ['class' => 'list-group-item']);
            }
            return Html::tag('ul', implode("", $li), ['class' => 'list-group']);
        }
    }

    public function reportPdf($table)
    {
        $filename = "smeta_" . $this->smeta_id . "_" . date("d.m.y h.i.s A") . ".pdf";
        $excelTable = new ExcelTable();
        $excelTable->table_id = $table->table_id;
        $excelTable->make($this);
        if ($excelTable->saveToPdf($filename)) {
            $file = new Files(['type' => Files::TYPE_PDF,'smeta_id' => $this->smeta_id,'link' => "/export/".$filename,'time' => time()]);
            $file->save();
            return $filename;
        }
        else return false;
    }

    public function copy()
    {
        $smeta = new Smeta();
        $smeta->name = $this->name;
        $smeta->current_stage = $this->current_stage;
        $smeta->user_id = $this->user_id;
        if ($this->history_of) {
            $smeta->history_of = $this->history_of;
        } else {
            D::alert(" MAKING FIRST CHILD OF SMETA_ID  = " . $this->smeta_id);
            $smeta->history_of = $this->smeta_id;
        }
        $smeta->save();

        /* @var $inputValue InputValue */

        if ($inputValues = $this->inputValues) {
            foreach ($inputValues as $inputValue) {
                $new_inputValue = new InputValue();
                $new_inputValue->input_id = $inputValue->input_id;
                $new_inputValue->value = $inputValue->value;
                $new_inputValue->smeta_id = $smeta->smeta_id;
                $new_inputValue->estimate_id = $inputValue->estimate_id;
                $new_inputValue->type = $inputValue->type;
                $new_inputValue->insert(false);
            }
        } else {
            D::alert(" NO $inputValue TO SMETA");
        }

        /* @var $outputValue OutputValue */

        if ($outputValues = $this->outputValues) {
            foreach ($outputValues as $outputValue) {
                $new_outputValue = new OutputValue();
                $new_outputValue->output_id = $outputValue->output_id;
                $new_outputValue->value = $outputValue->value;
                $new_outputValue->smeta_id = $smeta->smeta_id;
                $new_outputValue->stage_id = $outputValue->stage_id;
                $new_outputValue->insert(false);
            }
        } else {
            D::alert(" NO OutputValue TO SMETA");
        }

        /* @var $smetaEvent SmetaEvents */

        if ($smetaEvents = $this->events) {
            foreach ($smetaEvents as $smetaEvent) {
                $new_smeta_event = new SmetaEvents();
                $new_smeta_event->event_id = $smetaEvent->event_id;
                $new_smeta_event->value = $smetaEvent->value;
                $new_smeta_event->smeta_id = $smeta->smeta_id;
                $new_smeta_event->insert(false);
            }
        } else {
            D::alert(" NO SmetaEvents TO SMETA");
        }

        /* @var $EstimateToSmeta EstimatesToSmeta */

        if ($EstimatesToSmeta = $this->estimatesToSmeta) {

            foreach ($EstimatesToSmeta as $EstimateToSmeta) {
                $new_estimateToSmeta = new EstimatesToSmeta();
                $new_estimateToSmeta->estimate_id = $EstimateToSmeta->estimate_id;
                $new_estimateToSmeta->status = $EstimateToSmeta->status;
                $new_estimateToSmeta->priority = $EstimateToSmeta->priority;
                $new_estimateToSmeta->smeta_id = $smeta->smeta_id;
                if (!$new_estimateToSmeta->save()) Yii::$app->session->setFlash('danger', serialize($new_estimateToSmeta->errors));

            }
        } else {
            D::alert(" NO ESTIMATES TO SMETA");
        }

        return $smeta->smeta_id;

    }

    public static function forTest()
    {
        return self::find()->where(['forTest' => 1])->one();
    }

    public function ReplaceValue($value)
    {
        if ($value) {
            $new_value = $value;
            if ($variables = $this->variables) {
                foreach ($variables as $key => $variable) {
                    $old_value = $new_value;
                    $new_value = preg_replace("/" . $key . "/", $this->getVariables()[$key], $old_value);
                    /*   if ($new_value != $old_value) {
                           if (in_array($key, ["G17", "G18"])) D::success($key . " " . $variable . " VALUE = " . $new_value);
                       }*/

                }
            }

        }
        return $new_value;


    }

    public function addVariables($variable)
    {
        $this->variables = array_merge($variable, $this->variables);
    }

    public function getVariables()
    {
        return $this->variables;
    }


    public function mapEstimates()
    {
        return ArrayHelper::map(Estimate::find()->all(), 'estimate_id', 'name');
    }

    public function beforeDelete()
    {
        SmetaEvents::deleteAll(['smeta_id' => $this->smeta_id]);
        InputValue::deleteAll(['smeta_id' => $this->smeta_id]);
        OutputValue::deleteAll(['smeta_id' => $this->smeta_id]);
        EstimatesToSmeta::deleteAll(['smeta_id' => $this->smeta_id]);

        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if (!$this->created_at) $this->created_at = time();
        if (!$this->updated_at) $this->updated_at = time();

        return parent::beforeSave($insert);
    }

    public function getInputValues()
    {
        return $this->hasMany(InputValue::className(), ['smeta_id' => 'smeta_id']);
    }

    public function getOutputValues()
    {
        return $this->hasMany(OutputValue::className(), ['smeta_id' => 'smeta_id']);
    }

    public function getEvents()
    {
        return $this->hasMany(SmetaEvents::className(), ['smeta_id' => 'smeta_id']);
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
            if (!$estimatesPlus) {
                Yii::$app->session->setFlash('danger', 'Выберите смету');
                return false;
            }
            EstimatesToSmeta::updateAll(['status' => 0], ['smeta_id' => $this->smeta_id]);

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


        if (!isset($this->user_id)) $this->user_id = Yii::$app->user->identity->getId();
        return parent::beforeValidate();
    }

    public function getEstimates()
    {
        return $this->hasMany(Estimate::className(), ['estimate_id' => 'estimate_id'])
            ->viaTable(EstimatesToSmeta::tableName(), ['smeta_id' => 'smeta_id']);
    }

    public function getEstimatesToSmeta()
    {
        return $this->hasMany(EstimatesToSmeta::className(), ['smeta_id' => 'smeta_id']);
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
            [['user_id'], 'required'],
            [['estimate_id', 'history_of'], 'integer'],
            [['name'], 'string'],
            [['name'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->generateVariables();
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function generateVariables()
    {

        if ($estimates = $this->estimates) {
            foreach ($estimates as $estimate) {
                D::success(" ESTIMATE NAME = " . $estimate->name);
                if ($events = $estimate->events) {
                    foreach ($events as $event) {
                        // D::success(" EVENT NAME = " . $event->name);
                        if ($smeta_event = SmetaEvents::find()->where(['event_id' => $event->event_id])->andWhere(['smeta_id' => $this->smeta_id])->one()) {
                        } else {
                            $smetaEvent = new SmetaEvents(['smeta_id' => $this->smeta_id, 'event_id' => $event->event_id]);
                            $smetaEvent->value = 0;
                            if (!$smetaEvent->save()) D::dump($smetaEvent->errors);
                        }


                    }
                }

                if ($inputs = $estimate->inputs) {
                    foreach ($inputs as $input) {
                        D::success(" INPUT NAME = " . $input->name);
                        if ($inputValue = InputValue::find()->where(['input_id' => $input->input_id])->andWhere(['smeta_id' => $this->smeta_id])->one()) {

                            InputValue::updateAll(['type' => $input->type], ['AND', ['input_id' => $input->input_id], ['smeta_id' => $this->smeta_id]]);
                        } else {
                            $inputValue = new InputValue(['smeta_id' => $this->smeta_id, 'type' => $input->type, 'input_id' => $input->input_id, 'estimate_id' => $estimate->estimate_id]);
                            if (!$inputValue->save()) D::dump($inputValue->errors);
                        }


                    }
                }
            }
        }

    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getStagesId()
    {
        return EstimateStage::find()->where(['status' => EstimateStage::STATUS_ACTIVE])->andWhere(['estimate_id' => $this->estimatesId])->select('stage_id')->column();
    }

    public function getOutputsId()
    {


        /* @var $stage     EstimateStage */
        /* @var $estimate     Estimate */
        $stages_id = [];
        if ($estimates = $this->estimates) {
            foreach ($estimates as $estimate) {

                if ($stages_ids_estimate = $estimate->getStagesOutput()->select('stage_id')->column()) {

                    $stages_id = array_merge($stages_id, $stages_ids_estimate);

                };
            }
            $outputs_ids = Output::find()->where(['in', 'stage_id', $stages_id])->select('output_id')->column();
            return $outputs_ids;
        };
    }

    public function getEstimate()
    {
        return $this->hasOne(Estimate::className(), ['estimate_id' => 'estimate_id']);
    }

    public function getBodyMaterials()
    {
        $body = '';
        if ($outputs = Output::find()->where(['in', 'output_id', $this->getOutputsId()])
            ->andWhere([
                'OR',
                ['like', 'formula', 'material_'],
                ['like', 'result', 'material_'],
            ])->select('output_id,name,formula,result')->all()) {
            foreach ($outputs as $output) {
                $body .= " " . $output->formula;
                $body .= " " . $output->result;
            }


        }
        if ($table_cells = TableCells::find()->where(['like', 'value', 'material_'])->select('value')->column()) {
            foreach ($table_cells as $table_cell) {
                $body .= " " . $table_cell;
            }


        }
        return $body;
    }

    public function getBodyWorks()
    {
        $body = '';
        if ($outputs = Output::find()->where(['in', 'output_id', $this->getOutputsId()])
            ->andWhere([
                'OR',
                ['like', 'formula', 'work_'],
                ['like', 'result', 'work_'],
            ])->select('output_id,name,formula,result')->all()) {
            foreach ($outputs as $output) {
                $body .= " " . $output->formula;
                $body .= " " . $output->result;
            }


        }
        if ($table_cells = TableCells::find()->where(['like', 'value', 'work_'])->select('value')->column()) {
            foreach ($table_cells as $table_cell) {
                $body .= " " . $table_cell;
            }


        }
        return $body;
    }

    public function loadVariables($params = [])
    {
        if (!$params['materials_id']) $params['materials_id'] = Material::preg_match($this->getBodyMaterials());
        if (!$params['works_id']) $params['works_id'] = Works::preg_match($this->getBodyWorks());
        $variables = array_merge($this->loadEvents(), $this->loadInputs(), $this->loadOutputs(),
            $this->loadStation(), $this->loadMaterials($params['materials_id']),
            $this->loadManager(), $this->loadCity(), $this->loadWorks($params['works_id']));

        $this->variables = $variables;
        $this->variablesKeys = array_keys($this->variables);


        return $variables;

    }


    public function loadInputs()
    {

        /* @var $inputValue InputValue */

        if ($inputValues = InputValue::find()->where(['smeta_id' => $this->smeta_id])->all()) {
            foreach ($inputValues as $inputValue) {

                if (in_array($inputValue->type, [2, 3, 4])) {
                    if (!$inputValue->value) $inputValue->value = 0;
                } else {
                    $inputValue->value = "'" . $inputValue->value . "'";
                }
                $variables["input_" . $inputValue->input_id . "_"] = $inputValue->value;

            }

            return $variables;

        } else return [];
    }

    public function loadOutputs()
    {

        /* @var $inputValue InputValue */

        if ($outputValues = OutputValue::find()->where(['smeta_id' => $this->smeta_id])->joinWith('output as output')->all()) {
            foreach ($outputValues as $outputValue) {

                if (in_array($outputValue->output->type, [2, 3, 4])) {
                    if (!$outputValue->value) $outputValue->value = 0;
                } else {
                    $outputValue->value = "'" . $outputValue->value . "'";
                }
                $variables["output_" . $outputValue->output_id . "_"] = $outputValue->value;

            }

            return $variables;

        } else return [];
    }

    public function getTraces()
    {
        return $this->hasMany(Tracing::className(), ['smeta_id' => 'smeta_id']);
    }


    public function loadEvents()
    {

        /* @var $event SmetaEvents */

        if ($events = SmetaEvents::find()->where(['smeta_id' => $this->smeta_id])->all()) {
            foreach ($events as $event) {
                $variables["event_" . $event->event_id . "_"] = $event->value;
            }

            return $variables;

        } else return [];
    }

    public function loadMaterials($materials_id = [])
    {

        /* @var $material Material */

        if ($materials = Material::find()->where(['in', 'id', $materials_id])->all()) {
            foreach ($materials as $material) {
                if ($params = Material::$formulaParams) {
                    foreach ($params as $param) {
                        $variables[$material->getFormulaName() . "." . $param] = $material->$param;
                    }
                }

            }

            return $variables;

        } else return [];
    }

    public function loadWorks($works_id = [])
    {

        /* @var $work Works */

        if ($works = Works::find()->where(['in', 'id', $works_id])->all()) {
            foreach ($works as $work) {
                if ($params = Works::$formulaParams) {
                    foreach ($params as $param) {
                        $variables[$work->getFormulaName() . "." . $param] = $work->$param;
                    }
                }

            }

            return $variables;

        } else return [];
    }

    public function loadStation()
    {
        $variables = [];

        /* @var $event SmetaEvents */
        /* @var $station BaseStation */

        if ($estimate_ids = $this->getEstimatesId()) {
            $active_stages = EstimateStage::find()->select('stage_id')->where(['in', 'estimate_id', $estimate_ids])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->column();
        };
        // D::dump($estimate_ids);
        if ($input = Input::find()->where(['in', 'stage_id', $active_stages])->andWhere(['type' => Input::IN_LIST_BASE_STATION])->one()) {
            //  D::dump($input->input_id);
            if ($inputValueStation = InputValue::find()->where(['input_id' => $input->input_id])->andWhere(['smeta_id' => $this->smeta_id])->one()) {
                $station_id = $inputValueStation->value;
                //   D::success($station_id);
            }

            if ($station = BaseStation::find()->where(['id' => $station_id])->one()) {
                $attributes = $station->formulaLabels();
                foreach ($attributes as $key) {
                    $value = $station->$key;
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    } elseif (!$value) $value = 0;
                    $variables["station." . $key] = $value;
                }
            }
            if ($station) Yii::$app->session->set('station', $station);
            //   D::dump($station);

            return $variables;


        } else return [];
    }

    public function loadCity()
    {
        $variables = [];

        /* @var $event SmetaEvents */

        if ($estimate_ids = $this->getEstimatesId()) {
            $active_stages = EstimateStage::find()->select('stage_id')->where(['in', 'estimate_id', $estimate_ids])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->column();
        };
        // D::dump($estimate_ids);
        if ($input = Input::find()->where(['in', 'stage_id', $active_stages])->andWhere(['type' => Input::IN_LIST_OF_CITIES])->one()) {
            //  D::dump($input->input_id);
            if ($inputValueStation = InputValue::find()->where(['input_id' => $input->input_id])->andWhere(['smeta_id' => $this->smeta_id])->one()) {
                $station_id = $inputValueStation->value;
                //   D::success($station_id);
            }
            if ($station = City::find()->where(['id' => $station_id])->asArray()->one()) {
                foreach ($station as $key => $value) {
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) $value = "'" . $value . "'";
                    elseif (!$value) $value = 0;
                    $variables["city." . $key] = $value;
                }
            } else return [];
            if ($station) Yii::$app->session->set('city', $station);
            //   D::dump($station);

            return $variables;


        } else return [];

    }

    public function loadManager()
    {

        /* @var $event SmetaEvents */

        if ($estimate_ids = $this->getEstimatesId()) {
            $active_stages = EstimateStage::find()->select('stage_id')->where(['in', 'estimate_id', $estimate_ids])->andWhere(['status' => EstimateStage::STATUS_ACTIVE])->column();
        };
        // D::dump($estimate_ids);
        if ($input = Input::find()->where(['in', 'stage_id', $active_stages])->andWhere(['type' => Input::IN_LIST_OF_MANAGERS])->one()) {
            //  D::dump($input->input_id);
            if ($inputValueStation = InputValue::find()->where(['input_id' => $input->input_id])->andWhere(['smeta_id' => $this->smeta_id])->one()) {
                $station_id = $inputValueStation->value;
                //   D::success($station_id);
            }
            if ($station = User::find()->where(['id' => $station_id])->select('name,phone,email')->asArray()->one()) {
                foreach ($station as $key => $value) {
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) $value = "'" . $value . "'";
                    elseif (!$value) $value = 0;
                    $variables["manager." . $key] = $value;
                }
            }
            if ($station) Yii::$app->session->set('manager', $station);
            //   D::dump($station);

            return $variables;


        } else return [];
    }

    public function calcutale()
    {

        /* @var $estimate Estimate */
        /* @var $output Output */

        $variables = $this->loadVariables();
        if ($estimates = $this->estimates) {
            foreach ($estimates as $estimate) {
                D::success(" ESTIMATE  NAME = $estimate->name");

                // D::dump($inputsData);

                if ($stages = $estimate->stagesOutput) {
                    foreach ($stages as $stage) {
                        if ($outputs = $stage->outputs) {
                            D::alert("COUNT OUTPUTS = " . count($outputs));
                            foreach ($outputs as $output) {
                                $value = $output->evaluate($variables);
                                //  D::success("FORUMULA=  " . $formula);
                                //  D::success("VALUE =  " . $value);
                                if ($output_value = OutputValue::find()->where(['smeta_id' => $this->smeta_id])->andWhere(['output_id' => $output->output_id])->one()) {
                                } else {
                                    $output_value = new OutputValue(['smeta_id' => $this->smeta_id, 'output_id' => $output->output_id, 'stage_id' => $output->stage_id]);

                                }
                                $output_value->value = strval($value);
                                if (!$output_value->save()) D::dump($output_value->getErrors());


                            }
                        }
                    }

                };
            }
        };
        // D::success(" ESTIMATE_ID_" . $estimate->estimate_id);


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
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'history_of' => 'Копия ',
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

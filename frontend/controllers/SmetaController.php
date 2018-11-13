<?php

namespace frontend\controllers;

use backend\utils\D;
use common\models\EstimateStage;
use common\models\Events;
use common\models\Input;
use common\models\InputValue;
use common\models\SmetaEvents;
use Yii;
use common\models\Smeta;
use common\models\SmetaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmetaController implements the CRUD actions for Smeta model.
 */
class SmetaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionReport($id)

    {
        return $this->render('test', compact('id'));
    }


    /**
     * Lists all Smeta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmetaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Smeta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionReset($id)
    {
        $this->findModel($id)->generateVariables();
      //  return $this->redirect(['index']);

        return $this->render('_debug');
    }

    public function actionAddInputAjax($input_id)
    {
        $model = Input::findOne($input_id);

        return $this->renderAjax('_fill_input', compact('model'));
    }

    public function actionSaveInput()
    {
        /* @var $event Events */
        /* @var $input Input */


        $return = [];
        if ($_POST['data']) {
            $smeta = Yii::$app->session->get('smeta');
            if (!$smeta) D::alert("SMETA IS NOT DEFINED");
            $inputs_values = [];
            D::dump($_POST['data']);

            foreach ($_POST['data'] as $input) {
                if ($input['multiple']) {
                    if (preg_match_all('/\[(.+)\]/U', $input['name'], $output_array)) {
                        $output_array = $output_array[1];
                        krsort($output_array);
                    }


                    $input_index = preg_replace('/\[.+\]/', '', $input['name']);

                    $input_id = preg_replace("/\D+/", '', $input_index);

                    $inputInstanse = Input::findOne($input_id);
                    $columns[$input_id] = array_column($inputInstanse->getColumnsSchema(false), 'name');
                    // ;

                  $inputs_values[$input_id][] = $input['value'];
                } else {
                    $input_index = preg_replace('/\[.+\]/', '', $input['name']);

                    $input_id = preg_replace("/\D+/", '', $input_index);

                    $inputs_values[$input_id] = $input['value'];
                }
            }
            D::success(" COLUMNS");
            D::dump($columns);

            $multiple_values = array_filter($inputs_values, function ($item) {
                return is_array($item);
            });

            D::dump($multiple_values);


            $inputs_values = array_filter($inputs_values, function ($item) {
                return !is_array($item);
            });
            if ($multiple_values) {

                foreach ($multiple_values as $key => $inputs_value) {
                    D::success(" KEY  = " . $key);
                    if (count($columns[$key])) {
                        $items_new = [];
                        // ставляем мульти-group
                        if ($multiple_values_chuck = array_chunk($inputs_value, count($columns[$key]))) {

                            D::dump($multiple_values_chuck);
                            foreach ($multiple_values_chuck as $items) {

                                if (is_array($items)) {
                                    foreach ($items as $keyOfColumns => $item) {
                                        $items_new[$columns[$key][$keyOfColumns]] = $item;
                                    }
                                    //  D::dump($items_new);

                                }
                                $inputs_values_new[$key][] = $items_new;

                                // ;
                            }
                        }

                    } else {
                        // вставляем мульти поля
                        $inputs_values_new[$key] = $inputs_value;
                    }
                   // $inputs_values[$key] = $row;


                }

            } else {
                $inputs_values_new = [];
            }
            // D::dump($inputs_values_new);
            //
            $inputs_values = $inputs_values + $inputs_values_new;
            D::dump($inputs_values);
         //   D::dump(array_keys($inputs_values));

            if ($inputs = Input::find()->where(['in', 'input_id', array_keys($inputs_values)])->all()) {
                foreach ($inputs as $input) {
                    InputValue::deleteAll(['smeta_id' => $smeta->smeta_id, 'input_id' => $input->input_id]);

                    $responses[] = InputValue::set($smeta, $input, $inputs_values[$input->input_id]);


                    // ищем события, которые могут произойти при изменениии данного input
                    if ($events = Events::find()->where(['like', 'formula', $input->getFormulaName()])->all()) {
                        D::alert("EVENTS EXISTS");
                        foreach ($events as $event) {
                            D::alert("EVENT NAME " . $event->name);
                            if ($value = $event->check($smeta)) {
                                $message['event' . $event->event_id] = $value;
                            }
                        }

                    }
                    // ищем события, которые происходят от станции
                    if ($events = Events::find()->where(['like', 'formula', 'station'])->all()) {
                        D::alert("EVENTS EXISTS");
                        foreach ($events as $event) {
                            D::alert("EVENT NAME " . $event->name);
                            if ($value = $event->check($smeta)) {
                                $message['event' . $event->event_id] = $value;
                            }
                        }

                    }

                    D::dump($responses);
                    if ($responses) {
                        foreach ($responses as $response) {
                            if ($response['error']) {
                                // выводим ошибки в JS для информирования ошибок валидации
                                $return[$input->input_id] = $response['error'];
                            } else {
                                $return[$input->input_id] = '';
                            }
                        }
                    }
                    Smeta::updateAll(['current_stage' => $_POST['current_stage']], ['smeta_id' => $smeta->smeta_id]);


                }
            }

        }
        D::dump($message);

        //  $return[] = $inputs_values;
        // file_put_contents('log.json', json_encode($message));
        //file_put_contents('response.json', json_encode($return));
        //file_put_contents('posts.json', json_encode($_POST));
        if (Yii::$app->request->isAjax) return json_encode($return);
        else return $this->render('debug');

    }

    public function actionFillData($smeta_id, $stage = 1)
    {
        $smeta = Smeta::findOne($smeta_id);
        Yii::$app->session->set('smeta', $smeta);
        return $this->render('fill-smeta1', [
            'model' => $smeta,
            'stage' => $stage,
        ]);
    }

    public function actionCalculate($smeta_id)
    {
        $smeta = Smeta::findOne($smeta_id);
        $smeta->calcutale();
        return $this->render('calculate', [
            'model' => $smeta
        ]);
    }

    public function actionForTest($id) {

        if ($id) {
            Smeta::updateAll(['forTest' => 0]);
            Smeta::updateAll(['forTest' => 1],['smeta_id' => $id]);
        }

        return $this->redirect('index');
    }

    public function actionFillStage($stage_id)
    {
        $stage = EstimateStage::findOne($stage_id);
        $smeta = Yii::$app->session->get('smeta');
        return $this->renderAjax('_fill-stage', [
            'model' => $smeta,
            'stage' => $stage,
        ]);
    }

    /**
     * Creates a new Smeta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Smeta();
        if (!isset($model->name)) $model->name = Date('d/m/Y');
        $model->save();
        $model->generateVariables();

        /* if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['view', 'id' => $model->smeta_id]);
         }*/

        /* return $this->render('create', [
             'model' => $model,
         ]);*/

        return $this->redirect(['update', 'id' => $model->smeta_id]);

    }

    public function actionTestVariables()
    {
        $smeta = Smeta::findOne(4);
        D::success(" SMETA NAME =" . $smeta->name);

        if ($estimates = $smeta->estimates) {
            foreach ($estimates as $estimate) {
                D::success(" ESTIMATE NAME = " . $estimate->name);
                if ($events = $estimate->events) {
                    foreach ($events as $event) {
                        D::success(" EVENT NAME = " . $event->name);
                        if ($smeta_event = SmetaEvents::find()->where(['event_id' => $event->event_id])->andWhere(['smeta_id' => $smeta->smeta_id])->one()) {
                            $variables["event_" . $event->event_id . "_"] = $smeta_event->value;
                        } else   $variables["event_" . $event->event_id . "_"] = '';
                    }
                }

                if ($inputs = $estimate->inputs) {
                    foreach ($inputs as $input) {
                        D::success(" EVENT NAME = " . $input->name);
                        if ($inputValue = InputValue::find()->where(['input_id' => $input->input_id])->andWhere(['smeta_id' => $smeta->smeta_id])->one())
                            $variables["input_" . $input->input_id . "_"] = $inputValue->value;
                        else   $variables["input_" . $input->input_id . "_"] = '';
                    }
                }
            }
        }

        D::dump($variables);


        return $this->render('_debug');
    }

    /**
     * Updates an existing Smeta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->smeta_id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Smeta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Smeta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Smeta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Smeta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

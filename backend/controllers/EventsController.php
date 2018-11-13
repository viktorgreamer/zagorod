<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\Smeta;
use Yii;
use common\models\Events;
use common\models\EventsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Input;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
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

    public function actionTestEvents()
    {
        $smeta = Smeta::findOne(3);
        $events = [];
        D::alert("SMETA NAME = " . $smeta->name);
        if ($estimates = $smeta->estimates) {
            foreach ($estimates as $estimate) {
                if ($stages = $estimate->stages) {
                    foreach ($stages as $stage) {
                        if ($inputs = $stage->inputs) {
                            foreach ($inputs as $input) {
                                if ($input->event_id) $events[] = $input->event_id;
                            }
                        }
                        if ($stage->event_id) $events[] = $stage->event_id;
                    }
                }

            }


        }
        D::dump(array_unique($events));
        $events = Events::find()->where(['in', 'event_id', array_unique($events)])->all();
        foreach ($events as $event) {
            $result = $event->registerResultsTrue();
            D::echor($result);
        }


        return $this->render('_debug');
    }

    public function actionTestEvent()
    {

        $result = eval("return (1 == С);");
        D::dump($result);

        /* @var $event Events */

        $smeta = Smeta::findOne(3);
       // D::dump($smeta->toArray());
      if ( $events = Events::find()->all()) {
          foreach ($events as $event) {
            /*  D::success($event->name);
              D::success($event->formula);
              D::success($event->renderFormula($smeta));*/
            D::success("EVENT ID = ".$event->event_id);
              $event->check($smeta);
          }
      }

        return $this->render('_debug');
    }

    public function actionValidateEmail()
    {

        $value = 'an.viktor@ygmail.com';
        $reslonse = filter_var($value, FILTER_VALIDATE_EMAIL);

        $value = trim($value);
        if ($value != filter_var($value, FILTER_VALIDATE_EMAIL)) D::alert('VALIDATION ERROR');
        else D::success("VALIDATION SUCESSFULL");

        D::dump($reslonse);
        return $this->render('_debug');
    }

    public function actionAddDataToFormula($estimate_id)
    {
        $inputs = Input::find()->where(['estimate_id' => $estimate_id])->andWhere(['not in', 'type', [Input::STRING_TYPE, Input::IN_LIST_BASE_STATION]])->all();

        return $this->renderAjax('/output/_data', ['inputs' => $inputs]);
    }


    /**
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Events model.
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

    /**
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->event_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->event_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Events model.
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
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

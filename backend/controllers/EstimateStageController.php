<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\Input;
use Yii;
use common\models\EstimateStage;
use common\models\EstimateStageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EstimateStageController implements the CRUD actions for EstimateStage model.
 */
class EstimateStageController extends Controller
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

    /**
     * Lists all EstimateStage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EstimateStageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single EstimateStage model.
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
     * Creates a new EstimateStage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionChangeStatus($stage_id, $status_name, $status)
    {
        $model = EstimateStage::findOne($stage_id);

        $model->$status_name = $status;
        $model->debugSave();

    }

    public function actionCreateAjax($estimate_id,$type)
    {
        $model = new EstimateStage();
       // $model->type = $type;
        $model->estimate_id = $estimate_id;
        $model->status = EstimateStage::STATUS_ACTIVE;
        Yii::$app->session->set('current_estimate_id_admin',$estimate_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          if ($type == EstimateStage::TYPE_OUTPUT)  return $this->redirect(['estimate/view-output', 'id' => $model->estimate_id]);
          else  return $this->redirect(['estimate/view', 'id' => $model->estimate_id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }
    public function actionChangePriority($stage_id, $priority)
    {
        $model = EstimateStage::findOne($stage_id);
        $model->reorderPriority($priority);
    }


    public function actionCreate($estimate_id)
    {

        $model = new EstimateStage();
        $model->estimate_id = $estimate_id;
        Yii::$app->session->set('current_estimate_id_admin',$estimate_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                return $this->redirect(['estimate/view', 'id' => $model->estimate_id]);
            } else  return $this->redirect(['view', 'id' => $model->stage_id]);
        } else {
            if (Yii::$app->request->isAjax) return $this->renderAjax('create', [
                'model' => $model,
            ]);
            else return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EstimateStage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateAjax($stage_id)
    {
        $model = $this->findModel($stage_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['estimate/view', 'id' => $model->estimate_id]);

        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);


    }

    /**
     * Deletes an existing EstimateStage model.
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
     * Finds the EstimateStage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EstimateStage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EstimateStage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

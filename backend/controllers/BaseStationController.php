<?php

namespace backend\controllers;

use common\models\MaterialToStation;
use Yii;
use common\models\BaseStation;
use common\models\BaseStationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Material;


/**
 * BaseStationController implements the CRUD actions for BaseStation model.
 */
class BaseStationController extends Controller
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
     * Lists all BaseStation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseStationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddMaterial($station_id)
    {

        $materialToStation = new MaterialToStation();
        $materialToStation->station_id = $station_id;
        if ($materialToStation->load(Yii::$app->request->post())) {
            if ($material = Material::find()->where(['articul' => $materialToStation->articul])->one()) {
                if ($materialToStation->save()) {
                    Yii::$app->session->setFlash('success', 'Успешно добавили Материал');
                }
            } else Yii::$app->session->setFlash('danger', 'Товар не найден');
            $model = BaseStation::findOne($materialToStation->station_id);
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('_form_add_material', ['materialToStation' => $materialToStation]);
    }

    public function actionDeleteMaterialFromStation($id)
    {

        if ($materialToStation = MaterialToStation::find()->where(['id' => $id])->one()) {

            if ($materialToStation->delete()) {
                Yii::$app->session->setFlash('success', 'Успешно удалили Материал');

            }
            return $this->redirect(['view', 'id' => $materialToStation->station_id]);
        };

    }
    
    

    /**
     * Displays a single BaseStation model.
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
     * Creates a new BaseStation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseStation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BaseStation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BaseStation model.
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
     * Finds the BaseStation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseStation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseStation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

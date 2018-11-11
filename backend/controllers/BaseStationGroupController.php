<?php

namespace backend\controllers;

use common\models\BaseStation;
use common\models\BaseStationToGroup;
use common\models\Material;
use common\models\MaterialToGroup;
use Yii;
use common\models\BaseStationGroup;
use common\models\BaseStationGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BaseStationGroupController implements the CRUD actions for BaseStationGroup model.
 */
class BaseStationGroupController extends Controller
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
     * Lists all BaseStationGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseStationGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseStationGroup model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {


        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    public function actionAddMaterial($group_id)
    {

        $materialToGroup = new MaterialToGroup();
        $materialToGroup->group_id = $group_id;
        if ($materialToGroup->load(Yii::$app->request->post())) {
            if ($material = Material::find()->where(['articul' => $materialToGroup->articul])->one()) {
                if ($materialToGroup->save()) {
                    Yii::$app->session->setFlash('success', 'Успешно добавили Материал');
                }
            } else Yii::$app->session->setFlash('danger', 'Товар не найден');
            $model = BaseStationGroup::findOne($materialToGroup->group_id);
            return $this->redirect(['view', 'id' => $model->group_id]);
        }


        return $this->render('_form_add_material', ['materialToGroup' => $materialToGroup]);
    }

    public function actionDeleteMaterialFromGroup($id)
    {

        if ($materialToGroup = MaterialToGroup::findOne($id)) {

            if ($materialToGroup->delete()) {
                Yii::$app->session->setFlash('success', 'Успешно удалили Материал');
                return $this->redirect(['view', 'id' => $materialToGroup->group_id]);
            }
        };
    }


    public
    function actionAddStations($group_id)
    {
        if (($base_station_ids = Yii::$app->session->get('base_station_ids')) AND ($group_id)) {
            foreach ($base_station_ids as $base_station_id) {
                $base_station_to_group = new BaseStationToGroup(['group_id' => $group_id, 'station_id' => $base_station_id]);
                $base_station_to_group->save();
            }
        }
    }

    /**
     * Creates a new BaseStationGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public
    function actionCreate()
    {
        $model = new BaseStationGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->group_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BaseStationGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->group_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BaseStationGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BaseStationGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseStationGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = BaseStationGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

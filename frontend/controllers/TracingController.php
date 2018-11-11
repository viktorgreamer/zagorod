<?php

namespace frontend\controllers;

use backend\utils\D;
use common\models\Smeta;
use Yii;
use common\models\Tracing;
use common\models\TracingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TracingController implements the CRUD actions for Tracing model.
 */
class TracingController extends Controller
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
     * Lists all Tracing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TracingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tracing model.
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

    public function actionTree($id = 3)
    {
        /* @var $tracing Tracing */
        $smeta = Smeta::findOne($id);
        Yii::$app->session->set('smeta',$smeta);

        return $this->render('_tree', compact('smeta'));
    }


    /**
     * Creates a new Tracing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent_id = 0)
    {

        $model = new Tracing();
        $smeta = Yii::$app->session->get('smeta');
        $model->smeta_id = $smeta->smeta_id;

        if ($parent_id) $model->parent_id = intval($parent_id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($_POST['Tracing']['revisions']) $model->revisions =  $_POST['Tracing']['revisions'];
            if ($_POST['Tracing']['revisionCascades']) $model->revisionCascades = $_POST['Tracing']['revisionCascades'];
            if ($_POST['Tracing']['cascades']) $model->cascades = $_POST['Tracing']['cascades'];
            if ($_POST['Tracing']['turns']) $model->turns = $_POST['Tracing']['turns'];
            if ($_POST['Tracing']['floors']) $model->floors = $_POST['Tracing']['floors'];

            Smeta::updateAll(['current_stage' => 34], ['smeta_id' => $smeta->smeta_id]);
            return $this->redirect([Yii::$app->session->get('old_route'),'smeta_id' => $smeta->smeta_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateAjax($parent_id = 0)
    {
        $model = new Tracing();
        if ($parent_id) $model->parent_id = intval($parent_id);
        else $model->smeta_id = Yii::$app->session->get('smeta_id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            ///  D::dump($model->errors);
            return $this->redirect(['tree', 'id' => Yii::$app->session->get('smeta_id')]);

            // return 'success';
        } else {
            D::dump($model->errors);
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Updates an existing Tracing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        D::dump($_POST);
        $model = $this->findModel($id);
        $smeta = Yii::$app->session->get('smeta');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($_POST['Tracing']['revisions']) $model->revisions =  $_POST['Tracing']['revisions'];
            if ($_POST['Tracing']['revisionCascades']) $model->revisionCascades = $_POST['Tracing']['revisionCascades'];
            if ($_POST['Tracing']['cascades']) $model->cascades = $_POST['Tracing']['cascades'];
            if ($_POST['Tracing']['turns']) $model->turns = $_POST['Tracing']['turns'];
            if ($_POST['Tracing']['floors']) $model->floors = $_POST['Tracing']['floors'];
            Smeta::updateAll(['current_stage' => 34], ['smeta_id' => $smeta->smeta_id]);
            return $this->redirect([Yii::$app->session->get('old_route'),'smeta_id' => $smeta->smeta_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateAjax($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tree', 'id' => Yii::$app->session->get('smeta_id')]);

        } else return $this->renderPartial('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tracing model.
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

    public function actionDeleteAjax($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['tree', 'id' => Yii::$app->session->get('smeta_id')]);

    }

    /**
     * Finds the Tracing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tracing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tracing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\EstimateStage;
use Yii;
use common\models\Input;
use common\models\InputSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InputController implements the CRUD actions for Input model.
 */
class InputController extends Controller
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
     * Lists all Input models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InputSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Input model.
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
     * Creates a new Input model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionChangePriority($input_id, $priority)
    {
        $model = Input::findOne($input_id);
        $model->reorderPriority($priority);

    }

    public function actionMoveTo()
    {
        $inputs = $_POST['inputs'];
        $stage_id = $_POST['stage_id'];
        if (($inputs) && ( $stage_id)){
            $priority = Input::find()->where(['stage_id' => $stage_id])->max('priority');
            foreach ($inputs as $input) {
                $priority++;
                Input::updateAll(['priority' => $priority,'stage_id' => $stage_id],['input_id' => intval($input)]);
            }
        }
       D::dump($inputs);
       D::dump($stage_id);

    }

    public function actionCopyToOutput($input_id)
    {
        $model = Input::findOne($input_id);

        $model->copyToOutput();
    }

    public function actionCreate($stage_id)
    {

        $model = new Input();
        $model->stage_id = $stage_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->input_id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public
    function actionCreateAjax($stage_id)
    {

        $model = new Input();
        $model->stage_id = $stage_id;
        Yii::$app->session->set('current_estimate_stage_id_admin',$stage_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['estimate/view', 'id' => $model->estimate_id]);

        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Input model.
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
            return $this->redirect(['view', 'id' => $model->input_id]);

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public
    function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->set('current_estimate_stage_id_admin',$model->stage_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['estimate/view', 'id' => $model->estimate_id]);

        }
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Input model.
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

    public
    function actionDeleteAjax($id)
    {
        if ($result = $this->findModel($id)->delete()) {

            return $result;
        }
    }

    /**
     * Finds the Input model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Input the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Input::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

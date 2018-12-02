<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\Estimate;
use common\models\EstimateStage;
use common\models\Input;
use common\models\InputToOutput;
use common\models\Material;
use common\models\Smeta;
use Yii;
use common\models\Output;
use common\models\OutputSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OutputController implements the CRUD actions for Output model.
 */
class OutputController extends Controller
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
     * Lists all Output models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OutputSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChangePriority($output_id, $priority)
    {
        $model = Output::findOne($output_id);
        $model->reorderPriority($priority);
    }

    public function actionCreate($stage_id)
    {

        $model = new Output();
        $model->stage_id = $stage_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->output_id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public
    function actionCreateAjax($stage_id)
    {

        $model = new Output();
        $model->stage_id = $stage_id;
        if (!$model->estimate_id) {
            $stage = EstimateStage::findOne($model->stage_id);
            $model->estimate_id = $stage->estimate_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->set('current_estimate_stage_id_admin', $model->stage_id);
            return $this->redirect(['estimate/view-output', 'id' => $model->estimate_id]);

        } else {
            $model->save();
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Displays a single Output model.
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

    public function actionAddInput($input_id, $output_id)
    {
        $inputToOutput = new InputToOutput(['input_id' => $input_id, 'output_id' => $output_id]);
        $inputToOutput->save();
        $output = Output::findOne($output_id);
        return $this->renderAjax('_inputs', compact('output'));
    }

    public function actionSessionFormula()
    {
        if (Yii::$app->session->get('value')) return Yii::$app->session->get('formula') . "=" . Yii::$app->session->get('value');
        else return Yii::$app->session->get('eval');
    }

    public function actionCheck()
    {

        Yii::$app->session->set('NO_REPLACE', '');
        Yii::$app->session->set('error_message', '');
        Yii::$app->session->set('code', '');
        Yii::$app->session->set('eval', '');

        // $_REQUEST['debug_formula'] = true;
        if (($_POST['result'])) {

            if ($smeta = \common\models\Smeta::find()->where(['forTest' => 1])->one()) {
                $type = $_POST['type'];
                $formula = $_POST['formula'];
                $result = $_POST['result'];
            } else return "no-smeta-selected";

        } else {
            $smeta_id = 3;
            $output_id = 21;
            $smeta = Smeta::findOne($smeta_id);

        }


        $variables = $smeta->loadVariables(['materials_id' => Material::preg_match($smeta->getBodyMaterials() . $formula . $result)]);

        if (trim($result)) {
            $output = new Output();
            $output->type = $type;
            $output->result = $result;
            if (trim($formula)) $output->formula = $formula;

        } else {
            $output = Output::findOne($output_id);
        }

        D::dump($output->toArray());

        $response = $output->evaluate($variables);


        if (Yii::$app->request->isAjax) return json_encode($response);

        else return $this->render('_debug');

    }

    public function actionRenderInputs($output_id)
    {
        $output = Output::findOne($output_id);
        return $this->renderAjax('_inputs', compact('output'));
    }

    public
    function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->set('current_estimate_stage_id_admin', $model->stage_id);
            return $this->redirect(['estimate/view-output', 'id' => $model->estimate_id]);

        }
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionAddDataToFormula($estimate_id, $output_id)
    {
        $estimate = Estimate::findOne($estimate_id);
        $stagesIds = EstimateStage::find()->where(['estimate_id' => $estimate->estimate_id])->select('stage_id')->column();
        $inputs = Input::find()->where(['estimate_id' => $estimate_id])->andWhere(['stage_id' => $stagesIds])->all();

        return $this->renderAjax('_data', ['inputs' => $inputs, 'output_id' => $output_id]);
    }



    /**
     * Creates a new Output model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    /**
     * Updates an existing Output model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->output_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Output model.
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

    public function actionDeleteAjax()
    {

        $this->findModel($_POST['id'])->delete();

        return 'success';
    }

    /**
     * Finds the Output model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Output the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Output::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

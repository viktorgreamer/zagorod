<?php

namespace backend\controllers;

use common\models\ExcelTable;
use common\models\Smeta;
use common\models\TableHistory;
use Yii;
use common\models\Table;
use common\models\TableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TableController implements the CRUD actions for Table model.
 */
class TableController extends Controller
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
     * Lists all Table models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id)
    {
        return $this->render('edit', [
            'table_id' => $id,
        ]);
    }

    public function actionExcel($id)
    {
        return $this->render('_excel', [
            'table_id' => $id,
        ]);
    }

    public function actionExportExcel($id)
    {

        $filename = date("d_m_y_h_i_s_A") . ".xlsx";

        if ($id) {
            $excelTable = new ExcelTable();
            $excelTable->table_id = $id;
            $smeta = Smeta::forTest();
            $excelTable->make($smeta);
            $excelTable->saveToExcel($filename);
        }

        return $this->render('debug', [
            'filename' => $filename,
        ]);

    }

    public function actionExportPdf($id)
    {

        $filename = date("d\\m\\y h\\i\\s A") . ".pdf";
        if ($id) {
            $excelTable = new ExcelTable();
            $excelTable->table_id = $id;
            $excelTable->forClient = true;
            $smeta = Smeta::forTest();
            $excelTable->make($smeta);
            $excelTable->saveToPdf($filename);
        }
        return $this->render('debug', [
            'filename' => $filename,
        ]);

    }

    public
    function actionRestoreHistory()
    {

        if ($history_id = $_POST['history_id']) {
            if ($history = TableHistory::findOne(intval($history_id))) {
                $history->restore("Возврат к " . $history->name);
                return json_encode(['status' => 'success', 'history' => TableHistory::LastJson()]);
            } else return json_encode(['status' => 'error', "history " . $history_id . " was not found"]);


        } else return json_encode(['status' => 'error', 'history id was not sent']);

    }

    public
    function actionJsTests()
    {
        return $this->render('js_tests');
    }

    public
    function actionSaveVariables()
    {
        if (($_POST['variables']) AND ($_POST['table_id'])) {
            Table::updateAll(['variables' => $_POST['variables']], ['table_id' => $_POST['table_id']]);
        }

    }

    /**
     * Displays a single Table model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Table model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public
    function actionCreate()
    {
        $model = new Table();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->table_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Table model.
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
            return $this->redirect(['view', 'id' => $model->table_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Table model.
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
     * Finds the Table model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Table the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Table::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

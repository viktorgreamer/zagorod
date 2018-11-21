<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\Table;
use common\models\TableRows;
use kartik\base\WidgetAsset;
use Yii;
use common\models\TableCells;
use common\models\TableCellsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TableCellsController implements the CRUD actions for TableCells model.
 */
class TableCellsController extends Controller
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

    public function actionReset($table_id)
    {
        $table = Table::findOne($table_id);
        if ($table) $table->reset();
        /* @var $table Table */

        // return $this->render('_debug');
    }

    public function actionResetRows()
    {
        $table = Table::findOne(1);
        if ($table) {
            if ($rows = $table->getRows()->select('tr_id')->distinct()->orderBy('tr_id')->all()) {
                foreach ($rows as $row) {
                    $tr = new TableRows();
                    $tr->tr_id = $row->tr_id;
                    $tr->table_id = $table->table_id;
                    if (!$tr->save()) D::dump($tr->errors);

                }
            }
        }
        /* @var $table Table */

        return $this->render('_debug');
    }


    // public function actionAddRow($after,$table_id) {

    public function actionAddRow($table_id)
    {

        /* @var $table Table */

        if ($table = Table::findOne($table_id)) {
            $table_max_row = TableCells::find()->where(['table_id' => $table_id])->max('tr_id');
            D::dump($table_max_row);
            $CountCells = TableCells::find()->where(['table_id' => $table_id])->max('td_id');
            $tr = new TableRows();
            $tr->tr_id = $table_max_row + 1;
            $tr->table_id = $table->table_id;
            $counter = 0;
            D::dump($CountCells);
            do {
                $counter++;
                $cell = new TableCells(['tr_id' => $table_max_row + 1, 'table_id' => $table_id, 'td_id' => $counter, 'value' => '']);
                D::dump($cell->toArray());
                if (!$cell->save()) D::dump($cell->errors);
            } while ($counter < $CountCells);
        }
    }


    public
    function actionAddColumn($table_id)
    {

        /* @var $table Table */
        if ($table = Table::findOne($table_id)) {
            $table_max_column = TableCells::find()->where(['table_id' => $table_id])->max('td_id');
            D::dump($table_max_column);
            $CountCells = TableCells::find()->where(['table_id' => $table_id])->max('tr_id');
            $counter = 0;
            D::dump($CountCells);
            do {
                $counter++;
                $cell = new TableCells(['td_id' => $table_max_column + 1, 'table_id' => $table_id, 'tr_id' => $counter, 'value' => '']);
                D::dump($cell->toArray());
                if (!$cell->save()) D::dump($cell->errors);
            } while ($counter < $CountCells);

            return $this->render('_debug');


        }

        // return $this->render('_debug');
    }

    public
    function actionDeleteRow($table_id, $tr_id)
    {
        TableCells::deleteAll(['table_id' => $table_id, 'tr_id' => $tr_id]);
        $table = Table::findOne($table_id);
        if ($table) $table->reset();
        /* @var $table Table */

        return $this->render('_debug');
    }

    public
    function actionDeleteColumn($table_id, $td_id)
    {
        TableCells::deleteAll(['table_id' => $table_id, 'td_id' => $td_id]);
        $table = Table::findOne($table_id);
        if ($table) $table->reset();
        /* @var $table Table */

        return $this->render('_debug');
    }

    public
    function actionChangePriority()
    {
        $table_id = $_POST['table_id'];
        $tr_id = $_POST['tr_id'];
        $priority = $_POST['priority'];
        if ($table = Table::findOne($table_id)) {
            $table->reorderPriority($tr_id, $priority);
        }

    }

    public
    function actionChangePriorityColumn()
    {
        $table_id = $_POST['table_id'];
        $td_id = $_POST['td_id'];
        $priority = $_POST['priority'];
        if ($table = Table::findOne($table_id)) {
            $table->reorderPriorityColumn($td_id, $priority);
        }

    }


    public
    function actionChange()
    {
        if ($_POST['tr_id'] AND $_POST['attr'] AND $_POST['value'] AND $_POST['table_id']) {
            if ($_POST['td_id'] == 0) {
                $isUpdated = TableRows::updateAll(['result' => $_POST['value']], ['tr_id' => $_POST['tr_id'], 'table_id' => $_POST['table_id']]);
               return $isUpdated;
            } else {
                $isUpdated = TableCells::updateAll([$_POST['attr'] => $_POST['value']], ['tr_id' => $_POST['tr_id'], 'td_id' => $_POST['td_id'],'table_id' => $_POST['table_id']]);
                return $isUpdated;
            }


        }

    }

    /**
     * Lists all TableCells models.
     * @return mixed
     */
    public
    function actionIndex()
    {
        $searchModel = new TableCellsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TableCells model.
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
     * Creates a new TableCells model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public
    function actionCreate()
    {
        $model = new TableCells();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TableCells model.
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
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TableCells model.
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
     * Finds the TableCells model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TableCells the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = TableCells::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

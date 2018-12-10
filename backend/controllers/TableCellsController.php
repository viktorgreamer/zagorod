<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\Table;
use common\models\TableColumns;
use common\models\TableHistory;
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
            $history = TableHistory::storeNew('Добавить строку');
            $table_max_row = TableCells::find()->where(['table_id' => $table_id])->max('tr_id');
            D::dump($table_max_row);
            $CountCells = TableCells::find()->where(['table_id' => $table_id])->max('td_id');
            $tr = new TableRows();
            $tr->tr_id = $table_max_row + 1;
            $tr->table_id = $table->table_id;
            $tr->save();
            $counter = 0;
            D::dump($CountCells);
            do {
                $counter++;
                $cell = new TableCells(['tr_id' => $table_max_row + 1, 'table_id' => $table_id, 'td_id' => $counter, 'value' => '']);
                D::dump($cell->toArray());
                if (!$cell->save()) D::dump($cell->errors);
            } while ($counter < $CountCells);
            return json_encode(['status' => 1, 'history' => $history]);
        }
    }

    public function actionCopyRow($table_id, $tr_id)
    {

        /* @var $table Table */

        if ($table = Table::findOne($table_id)) {
            $history = TableHistory::storeNew('Копировать строку');
            $table_max_row = TableCells::find()->where(['table_id' => $table_id])->max('tr_id');
            D::dump($table_max_row);
            $CountCells = TableCells::find()->where(['table_id' => $table_id])->max('td_id');
            $tr = new TableRows();
            $tr->tr_id = $table_max_row + 1;
            $tr->table_id = $table->table_id;
            $tr->save();
            $counter = 0;
            D::dump($CountCells);
            if ($tr_id) {
                if ($cells = TableCells::find()->where(['table_id' => $table_id])->andWhere(['tr_id' => $tr_id])->all()) {
                    foreach ($cells as $cell) {
                        $counter++;
                        $new_cell = new TableCells(['tr_id' => $table_max_row + 1, 'table_id' => $table_id, 'td_id' => $cell->td_id]);
                        $new_cell->classes = $cell->classes;
                        $new_cell->value = $cell->value;
                        $new_cell->colspan = $cell->colspan;
                        //   $new_cell->align = $cell->align;
                        D::dump($new_cell->toArray());
                        if (!$new_cell->save()) D::dump($new_cell->errors);
                    }
                }
            }
            return json_encode(['status' => 1, 'history' => $history]);

        }
    }


    public
    function actionAddColumn($table_id)
    {

        /* @var $table Table */
        if ($table = Table::findOne($table_id)) {
            $history = TableHistory::storeNew('Добавить столбец');
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

            $column = new TableColumns(['td_id' => $table_max_column + 1, 'table_id' => $table_id, 'width' => '10']);
            $column->save();
            return json_encode(['status' => 1, 'history' => $history]);
            // return $this->render('_debug');


        }

        // return $this->render('_debug');
    }

    public
    function actionDeleteRow($table_id, $tr_id)
    {
        $history = TableHistory::storeNew('Удалили строку');
        TableCells::deleteAll(['table_id' => $table_id, 'tr_id' => $tr_id]);
        $table = Table::findOne($table_id);
        if ($table) $table->reset();
        return json_encode(['status' => 1, 'history' => $history]);
        /* @var $table Table */

    }

    public function actionCombine()
    {
        if (($_POST['table_id']) AND ($_POST['tr_id']) AND ($_POST['td_to_delete']) AND $_POST['td_col_span']) {
            $history = TableHistory::storeNew('Убьеденить ячейки');
            $colspan_colspan = TableCells::find()
                ->where(['td_id' => $_POST['td_col_span']])
                ->andWhere(['tr_id' => $_POST['tr_id']])
                ->andWhere(['table_id' => $_POST['table_id']])
                // ->select('colspan')
                ->one();
            $colspan_delete = TableCells::find()
                ->where(['td_id' => $_POST['td_to_delete']])
                ->andWhere(['tr_id' => $_POST['tr_id']])
                ->andWhere(['table_id' => $_POST['table_id']])
                // ->select('colspan')
                ->one();
            if (!$colspan_colspan->colspan) $colspan_colspan->colspan = 1;
            if (!$colspan_delete->colspan) $colspan_delete->colspan = 1;
            TableCells::deleteAll(['td_id' => $_POST['td_to_delete'], 'tr_id' => $_POST['tr_id'], 'table_id' => $_POST['table_id']]);

            $colspan = $colspan_colspan->colspan + $colspan_delete->colspan;
            TableCells::updateAll(['colspan' => $colspan], ['td_id' => $_POST['td_col_span'], 'tr_id' => $_POST['tr_id'], 'table_id' => $_POST['table_id']]);
            return json_encode(['status' => 1, 'history' => $history]);
        }


    }

    public
    function actionFormat()
    {
        D::$isLogToFile = true;
        D::dump($_POST);
        /* @var $cell TableCells */
        if (($_POST['table_id']) AND ($_POST['tr_id']) AND ($_POST['td_id'])) {
            $history = TableHistory::storeNew('Отменить ' . TableCells::formatText($_POST['format']));
            $cell = TableCells::find()->where(['AND', ['td_id' => $_POST['td_id'], 'tr_id' => $_POST['tr_id'], 'table_id' => $_POST['table_id']]])->one();
            $cell->toggleClass($_POST['format']);

            $cell->update(false);
            return json_encode(['status' => 1, 'history' => $history]);
        }
    }

    public
    function actionMultiFormat()
    {
        D::$isLogToFile = true;
        D::dump($_POST);
        D::dump($_POST['addresses']);
        /* @var $cell TableCells */
        if (($_POST['table_id']) AND ($_POST['addresses'])) {
            $history = TableHistory::storeNew('Отменить ' . TableCells::formatText($_POST['format']));
            $cells = TableCells::find()->where(['AND', ['in', 'address', $_POST['addresses'], 'table_id' => $_POST['table_id']]])->all();
            if ($cells) {
                foreach ($cells as $cell) {
                    $cell->addClass($_POST['format']);
                    $cell->update(false);
                }
            }
            return json_encode(['status' => 1, 'history' => $history]);
        }
    }

    public
    function actionMultiSetType()
    {
        D::$isLogToFile = true;
        D::dump($_POST);
        D::dump($_POST['addresses']);
        /* @var $cell TableCells */
        if (($_POST['table_id']) AND ($_POST['addresses']) AND ($_POST['type'])) {
            $history = TableHistory::storeNew('Отменить тип');
            $cells = TableCells::find()->where(['AND', ['in', 'address', $_POST['addresses'], 'table_id' => $_POST['table_id']]])->all();
            if ($cells) {
                foreach ($cells as $cell) {
                    $cell->type = $_POST['type'];
                    $cell->update(false);
                }
            }
            return json_encode(['status' => 1, 'history' => $history]);
        }
    }


    public
    function actionDeleteColumn($table_id, $td_id)
    {

        $history = TableHistory::storeNew('Удалить столбец');
        TableCells::deleteAll(['table_id' => $table_id, 'td_id' => $td_id]);
        TableColumns::deleteAll(['table_id' => $table_id, 'td_id' => $td_id]);
        $table = Table::findOne($table_id);
        if ($table) $table->reset();
        /* @var $table Table */
        return json_encode(['status' => 1, 'history' => $history]);

    }

    public
    function actionToggleVisibilityColumn()
    {
        D::$isLogToFile;
        D::dump($_POST);
        if ($_POST['table_id'] && $_POST['td_id']) {

            if ($column = TableColumns::find()->where(['table_id' => $_POST['table_id']])->andWhere(['td_id' => $_POST['td_id']])->one()) {
                if ($column->hidden) {
                    $isUpdated = TableColumns::updateAll(['hidden' => TableColumns::NOT_HIDDEN], ['AND', ['table_id' => $_POST['table_id'], 'td_id' => $_POST['td_id']]]);
                    $history = TableHistory::storeNew('Скрыть столбец');
                } else {
                    $isUpdated = TableColumns::updateAll(['hidden' => TableColumns::HIDDEN], ['AND', ['table_id' => $_POST['table_id'], 'td_id' => $_POST['td_id']]]);
                    $history = TableHistory::storeNew('Показать столбец');
                }
            }

            /* @var $table Table */
            return json_encode(['status' => $isUpdated, 'history' => $history]);

        } else return json_encode(['status' => 0, 'history' => '']);

    }

    public
    function actionChangePriority()
    {
        $table_id = $_POST['table_id'];
        $tr_id = $_POST['tr_id'];
        $priority = $_POST['priority'];
        if ($table = Table::findOne($table_id)) {
            $history = TableHistory::storeNew('Поменять строки местами');
            $table->reorderPriority($tr_id, $priority);
            return json_encode(['status' => 1, 'history' => $history]);
        }

    }

    public
    function actionChangePriorityColumn()
    {
        $table_id = $_POST['table_id'];
        $td_id = $_POST['td_id'];
        $priority = $_POST['priority'];
        if ($table = Table::findOne($table_id)) {
            $history = TableHistory::storeNew('Поменять столбцы местами');
            $table->reorderPriorityColumn($td_id, $priority);

            return json_encode(['status' => 1, 'history' => $history]);
        }

    }

    public
    function actionChangeWidth()
    {
        $table_id = $_POST['table_id'];
        $td_id = $_POST['td_id'];
        $width = $_POST['width'];
        D::dump($_POST);
        D::success(intval($width));

        if ($column = TableColumns::find()->where(['table_id' => $table_id])->andWhere(['td_id' => $td_id])->one()) {
            $history = TableHistory::storeNew('Поменять ширину столбца');
            $width = $column->width + intval($width);
            if ($width < 0) $width = 0;
            $isUpdated = TableColumns::updateAll(['width' => $width], ['table_id' => $table_id, 'td_id' => $td_id]);
            return json_encode(['status' => $isUpdated, 'history' => $history]);
        }


    }

    public function JsonResponse($response)
    {
        return json_decode($response);
    }


    public
    function actionChange()
    {
        if ($_POST['tr_id'] AND $_POST['table_id']) {
            $history = TableHistory::storeNew('Ввод данных ' . $_POST['value']);
            if (!$_POST['td_id']) {
                $isUpdated = TableRows::updateAll(['result' => $_POST['value']], ['tr_id' => $_POST['tr_id'], 'table_id' => $_POST['table_id']]);
                return json_encode(['status' => $isUpdated, 'history' => $history]);
            } else {
                $isUpdated = TableCells::updateAll([$_POST['attr'] => $_POST['value']], ['tr_id' => $_POST['tr_id'], 'td_id' => $_POST['td_id'], 'table_id' => $_POST['table_id']]);
                return json_encode(['status' => $isUpdated, 'history' => $history]);
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

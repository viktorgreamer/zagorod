<?php

namespace backend\controllers;

use backend\utils\D;
use common\models\BaseStation;
use common\models\BaseStationCalculateType;
use common\models\City;
use common\models\Material;
use common\models\Output;
use common\models\Smeta;
use common\models\ValidationRule;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionFillCalculateType()
    {
        /*$file = file_get_contents("uploads/calculate_type.csv");

        $file = array_unique(preg_split("/;/", $file));

        D::dump($file);

        foreach ($file as $item) {
            $calculate_type = new BaseStationCalculateType();
            $calculate_type->name = trim($item);
            $calculate_type->save();
        }*/

        $calculate_types = ArrayHelper::map(BaseStationCalculateType::find()->all(), 'name', 'id');
        D::dump($calculate_types);
        return $this->render('index');
    }

    public function actionImportStationBase()
    {

        BaseStation::deleteAll();

        $importer = new CSVImporter();

        //Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => "uploads/2.csv",
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));

        // Транспонируем массив
        $data = $importer->getData();
        //  array_shift($data);
        // D::dump($data);


        // $data = call_user_func_array("array_map", $data);
        //  D::dump($data[2]);

        $baseStation = new BaseStation();
        $attributes = array_keys($baseStation->attributes);
        array_shift($attributes);
        //   D::dump($attributes);
        $numeric_types = BaseStation::numericProperties();
        $integer_types = BaseStation::integerProperties();
        foreach ($data as $item) {
            $baseStation = new BaseStation();
            foreach ($item as $field => $value) {
                $attribute = $attributes[$field];
                if ($attribute) {
                    if ($attribute == 'measure') {
                        $measures = array_flip($baseStation->mapMeasure());
                        $baseStation->$attribute = $measures[$value];
                    } elseif ($attribute == 'sp') {
                        $measures = array_flip($baseStation->mapSp());
                        $baseStation->$attribute = $measures[$value];
                    } elseif ($attribute == 'fecal_nas') {
                        $measures = array_flip($baseStation->mapFecalnas());
                        $baseStation->$attribute = $measures[$value];
                    } elseif ($attribute == 'type_calculate_id') {
                        $calculate_types = ArrayHelper::map(BaseStationCalculateType::find()->all(), 'name', 'id');
                        $baseStation->$attribute = $calculate_types[$value];
                    } else {
                        if (in_array($attribute, $numeric_types)) {
                            $value = round(preg_replace("/,/", '.', $value), 2);
                        }
                        if (in_array($attribute, $integer_types)) {
                            $value = round(preg_replace("/,/", '.', $value));
                        }
                        $baseStation->$attribute = $value;
                    }

                }
                //  D::success(" TRY TO SET ATTRIBUTE " . $attribute . " VALUE " . $value);

            }
            //   D::dump($baseStation->toArray());
            $baseStation->debugSave();
            //  break;
        }


        return $this->render('index');


    }

    public function actionTestPhp()
    {

        $message = urlencode('implode(,", unserialize(\'a:2:{i:0;s:15:"8(963) 240-9945";i:1;s:14:"8(967)667-6667";}\'));');

        // Get cURL resource
        $curl = curl_init();
// Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://phpcodechecker.com/api/?code=$message",
            // CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
// Send the request & save response to $resp
        $response = curl_exec($curl);
// Close request to clear up some resources
        curl_close($curl);


        //  $response = file_get_contents();

        $response = json_decode($response);
        if ($response->errors == 'TRUE') {
            if ($message = $response->syntax->message) {
                $message = preg_replace("/Parse error: syntax error, unexpected/", "Синтактическая ошибка, не ожидается ", $message);
                $message = preg_replace("/ in your code on line/", " в формуле в линии ", $message);
                D::alert($message);
            }
            D::dump($response);
            D::alert(" ERRORS");
        } else {
            D::dump($response);
        }
        return $this->render('index');


    }

    public function actionImportMaterial()
    {

        //  BaseStation::deleteAll();

        $importer = new CSVImporter();

        //Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => "uploads/material.csv",
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));

        // Транспонируем массив
        $data = $importer->getData();
        //  array_shift($data);
        //  D::dump($data[1]);


        // $data = call_user_func_array("array_map", $data);
        //  D::dump($data[2]);

        $material = new Material();
        $attributes = array_keys($material->attributes);
        array_shift($attributes);
        //  D::dump($attributes);

        $integer_types = Material::integerProperties();
        foreach ($data as $item) {
            $material = new Material();

            foreach ($item as $field => $value) {
                $attribute = $attributes[$field];
                if ($attribute) {
                    if ($attribute == 'measure') {
                        $measures = array_flip($material->mapMeasure());
                        $material->$attribute = $measures[$value];
                    } elseif ($attribute == 'complex_of_works') {
                        $measures = array_flip($material->mapComplexOfWork());
                        $material->$attribute = $measures[$value];
                    } elseif ($attribute == 'product_type') {
                        $measures = array_flip($material->mapProductType());
                        $material->$attribute = $measures[$value];
                    } elseif ($attribute == 'material_type') {
                        $measures = array_flip($material->mapMaterialType());
                        $material->$attribute = $measures[$value];
                    } elseif ($attribute == 'sg_sht') {
                        $measures = array_flip($material->mapSgSht());
                        $material->$attribute = $measures[$value];
                    } elseif ($attribute == 'type_cost') {
                        $measures = array_flip($material->mapTypeCost());
                        $material->$attribute = $measures[$value];
                    } else {

                        if (in_array($attribute, $integer_types)) {
                            $value = round(preg_replace("/,/", '.', $value));
                        }
                        $material->$attribute = $value;
                    }

                }
                //  D::success(" TRY TO SET ATTRIBUTE " . $attribute . " VALUE " . $value);

            }
            //   D::dump($baseStation->toArray());
            $material->debugSave();
            // break;
        }


        return $this->render('index');


    }

    public function actionCheckMaterials()
    {
        $smeta = Smeta::findOne(3);
        if ($body = $smeta->getBodyMaterials()) {
            $variables = $smeta->loadVariables(['materials_id' => Material::preg_match($body)]);
            D::dump($variables);
        }
        return $this->render('index');
    }

    public function actionTable() {

        return $this->render('_table');
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            /*   'access' => [
                   'class' => AccessControl::className(),
                   'rules' => [
                       [
                           'actions' => ['login', 'error'],
                           'allow' => true,
                       ],
                       [
                           'actions' => ['logout', 'index'],
                           'allow' => true,
                           'roles' => ['@'],
                       ],
                   ],
               ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionEval()
    {
        $a = 3;
        $b = 7;
        $c = 5;
        $d = 2.6;
        $string = "return round($c*($a + $b)/$d,3);";
        $eval = eval($string);
        D::success($eval);
        return $this->render('index');
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

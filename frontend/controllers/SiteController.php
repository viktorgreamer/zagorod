<?php

namespace frontend\controllers;

use app\utils\D;
use common\models\User;
use ruskid\csvimporter\CSVImporter;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            /* 'access' => [
                 'class' => AccessControl::className(),
                 'only' => ['logout', 'signup'],
                 'rules' => [
                     [
                         'actions' => ['signup'],
                         'allow' => true,
                         'roles' => ['?'],
                     ],
                     [
                         'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMultiGroup()
    {
        return $this->render('multigroup');
    }

       public function actionAutoLogin()
    {
        $managers = "Павел Пучков	7(904)160-89-06	pavel_3agorod@icloud.com
Василий Газизуллин	7(963)850-57-77	vasiliy.3agorod@yandex.ru
Виталий Седунов	7(963)327-32-70	v-sedunov@yandex.ru
Денис Трофимов	7(921)883-82-83	info@3agorod.ru
Игорь Карпов	7(965)033-56-72	rop@3agorod.ru
Игорь Чернышев	7(962)725-84-25	3agorod@inbox.ru
Алексей Боталов	7(921)971-04-06	manager3@3agorod.ru
Александр Мелузов	7(969)731-18-79	manager2@3agorod.ru
Леонид Кузьмицкий	7(969)729-87-18	manager4@3agorod.ru
Денис Смирнов	7(964)322-53-37	denis@otoplenie-doma-spb.ru
Сергей Логвинов	7(963)308-10-85	lsa@3agorod.ru
Александр Салмин	7(969)799-33-68	b2b@3agorod.ru
Владимир Утко	7(921)217-60-07	utko-1@yandex.ru
Алексей Усков	7(962)729-57-70	manager5@3agorod.ru
Роман Тихонов	7(963)308-12-06	manager1@3agorod.ru";
        $managers = explode("\n", $managers);
        foreach ($managers as $manager) {
            $data = preg_split("/\s/", $manager);
            // \backend\utils\D::dump($data);
            $login = preg_split("/@/", $data[3]);
            $user = new User();
            $user->name = $data[0];
            $user->surname = $data[1];
            $user->username = $login[0];
            $user->email = $data[3];
            $user->phone = "+".$data[2];
            $user->setPassword($login[0]);
            $user->generateAuthKey();
            if (!$user->save()) \backend\utils\D::dump($user->getErrors());
            \backend\utils\D::dump($user->toArray());
        }
        \backend\utils\D::dump($managers);
        /*
                    $user = new User();
                $user->username = $this->username;
                $user->email = $this->email;
                $user->setPassword($this->password);
                $user->generateAuthKey();*/

        return $this->render('index');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

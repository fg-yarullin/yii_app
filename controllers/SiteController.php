<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\ContactForm;
use app\models\ProfileForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
//            \yii\web\Controller::redirect('/site/profile');
//           return $this->render('confirmationsent');
            $model->is_registered= true;
            $message = 'Регистрация прошла успешно. На вашу электронную почту отравлено письмо со ссылкой для активации учетной записи.';
//            Yii::$app->session->setFlash('success', "Your message to display.");
            return $this->goBack(['model' => $model,], Yii::$app->session->setFlash('success', $message));
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->actionRegister();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            \yii\web\Controller::redirect('/site/profile');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionConfirmationSent() {
        return $this->render('confirmationsent');
    }

    public function actionActivation() {
        if ($activation_key = Yii::$app->request->get('activation_key')) {
            $user = User::findIdentityByActivationKey($activation_key);
            $user->is_active = true;
            $user->save();
            Yii::$app->user->login($user,  360);
            return $this->goHome();
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionProfile()
    {
        $model = new ProfileForm();
        if ($user = User::findIdentity(Yii::$app->user->id)) {
            $model->showProfile($user->attributes);
            $keys = ['surname', 'name', 'password', 'confirm_password'];

            if ($model->load(Yii::$app->request->post())) {

                foreach ($model as $key => $value) {
                    if (in_array($key, $keys) && !!$value) {
                        if ($key === 'password') {
                            $user->$key = Yii::$app->security->generatePasswordHash($value);
                        } else {
                            $user->$key = $value;
                        }
                    }
                }
                $user->update();

                return $this->refresh();
            }

            return $this->render('profile', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteprofile()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        if (Yii::$app->request->post()) {
            $user->delete();
            return $this->goHome();
        }
        return $this->render('deleteprofile', ['user' => $user]);
    }
}

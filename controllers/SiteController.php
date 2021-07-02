<?php

namespace app\controllers;

use app\models\Browser;
use app\models\Log;
use app\models\LogFilterForm;
use app\models\System;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
        $query = Log::find()->joinWith(['system', 'browser']);

        $filter = new LogFilterForm();
        if ($filter->load(Yii::$app->request->queryParams) && $filter->validate()) {
            $filter->apply($query);
        }

        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $dataProvider->sort->attributes['system.name'] = [
            'asc' => ['system.name' => SORT_ASC],
            'desc' => ['system.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['browser.name'] = [
            'asc' => ['browser.name' => SORT_ASC],
            'desc' => ['browser.name' => SORT_DESC],
        ];

        $systems = System::find()->orderBy('name')->all();
        $systemNames = array_column($systems, 'name', 'id');

        $browsers = Browser::find()->orderBy('name')->all();
        $browserNames = array_column($browsers, 'name', 'id');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
            'systemNames' => $systemNames,
            'browserNames' => $browserNames,
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
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
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
    public function actionAbout()
    {
        return $this->render('about');
    }
}

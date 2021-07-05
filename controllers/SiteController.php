<?php

namespace app\controllers;

use app\models\LogFilterForm;
use app\models\LogStat;
use app\models\System;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = LogStat::find()->joinWith(['topBrowser tb']);

        $filter = new LogFilterForm();
        if ($filter->load(Yii::$app->request->queryParams) && $filter->validate()) {
            $filter->apply($query);
        } else {
            $emptyFilter = new LogFilterForm();
            $emptyFilter->apply($query);
        }

        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $dataProvider->sort->attributes['topBrowser.name'] = [
            'asc' => ['tb.name' => SORT_ASC],
            'desc' => ['tb.name' => SORT_DESC],
        ];
        $dataProvider->sort->defaultOrder = ['date' => SORT_DESC];

        $systems = System::find()->orderBy('name')->all();
        $systemNames = array_column($systems, 'name', 'id');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
            'systemNames' => $systemNames,
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

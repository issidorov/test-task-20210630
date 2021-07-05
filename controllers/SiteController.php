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

        $defaultEndDate = LogStat::find()->max('date') ?: date('Y-m-d');
        $defaultBeginDate = date('Y-m-d', strtotime($defaultEndDate . ' - 1 month'));

        $filter = new LogFilterForm($defaultBeginDate, $defaultEndDate);
        $filter->load(Yii::$app->request->queryParams);

        if ($filter->validate()) {
            $filter->apply($query);
            $dataProvider = new ActiveDataProvider(['query' => $query]);
            $dataProvider->sort->attributes['topBrowser.name'] = [
                'asc' => ['tb.name' => SORT_ASC],
                'desc' => ['tb.name' => SORT_DESC],
            ];
            $dataProvider->sort->defaultOrder = ['date' => SORT_DESC];
        } else {
            $dataProvider = null;
        }

        $systems = System::find()->orderBy('name')->all();
        $systemNames = array_column($systems, 'name', 'id');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
            'systemNames' => $systemNames,
        ]);
    }
}

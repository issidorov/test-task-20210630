<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $filter \app\models\LogFilterForm */
/* @var $systemNames string[] */
/* @var $browserNames string[] */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-6">
            <?= \dosamigos\chartjs\ChartJs::widget([
                'type' => 'line',
                'options' => [
                    'height' => 150,
                    'width' => 300,
                ],
                'data' => [
                    'labels' => ["January", "February", "March", "April", "May", "June", "July"],
                    'datasets' => [
                        [
                            'label' => "My First dataset",
                            'backgroundColor' => "rgba(179,181,198,0.2)",
                            'borderColor' => "rgba(179,181,198,1)",
                            'pointBackgroundColor' => "rgba(179,181,198,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(179,181,198,1)",
                            'data' => [65, 59, 90, 81, 56, 55, 40]
                        ],
                    ]
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= \dosamigos\chartjs\ChartJs::widget([
                'type' => 'line',
                'options' => [
                    'height' => 150,
                    'width' => 300,
                ],
                'data' => [
                    'labels' => ["January", "February", "March", "April", "May", "June", "July"],
                    'datasets' => [
                        [
                            'label' => "My First dataset",
                            'backgroundColor' => "rgba(179,181,198,0.2)",
                            'borderColor' => "rgba(179,181,198,1)",
                            'pointBackgroundColor' => "rgba(179,181,198,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(179,181,198,1)",
                            'data' => [65, 59, 90, 81, 56, 55, 40]
                        ],
                        [
                            'label' => "My Second dataset",
                            'backgroundColor' => "rgba(255,99,132,0.2)",
                            'borderColor' => "rgba(255,99,132,1)",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => [28, 48, 40, 19, 96, 27, 100]
                        ]
                    ]
                ]
            ]); ?>
        </div>
    </div>


    <?php $form = \yii\bootstrap\ActiveForm::begin(['action' => ['site/index'], 'method' => 'get']) ?>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($filter, 'begin')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($filter, 'end')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($filter, 'system')->dropDownList($systemNames, ['prompt' => 'Не выбрано']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($filter, 'browser')->dropDownList($browserNames, ['prompt' => 'Не выбрано']) ?>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button class="btn btn-primary" type="submit">Применить</button>
                </div>
            </div>
        </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end() ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'ip',
            'time:datetime',
            [
                'attribute' => 'url',
                'value' => function(\app\models\Log $log) {
                    return \yii\helpers\StringHelper::truncate($log->url, 60);
                },
            ],
            'system.name:text:ОС',
            'browser.name:text:Браузер',
        ],
    ]) ?>
</div>

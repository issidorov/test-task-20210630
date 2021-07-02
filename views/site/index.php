<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">
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

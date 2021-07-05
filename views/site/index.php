<?php

use app\models\LogFilterForm;
use app\widgets\BrowserRequestsChart;
use app\widgets\TotalRequestsChart;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider ActiveDataProvider|null */
/* @var $filter LogFilterForm */
/* @var $systemNames string[] */

?>
<div class="site-index">
    <?php if (!$filter->hasErrors()): ?>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <?= TotalRequestsChart::widget(['filter' => $filter]); ?>
                </div>
                <div class="col-md-6">
                    <?= BrowserRequestsChart::widget(['filter' => $filter]); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php $form = ActiveForm::begin(['action' => ['site/index'], 'method' => 'get']) ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($filter, 'begin')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($filter, 'end')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($filter, 'system')->dropDownList($systemNames, ['prompt' => 'Не выбрано']) ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button class="btn btn-primary" type="submit">Применить фильтр</button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>

    <?php if (!$filter->hasErrors()): ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'date',
                'requests_count',
                'top_url',
                'topBrowser.name:text:Популярный браузер',
            ],
        ]) ?>
    <?php endif; ?>
</div>

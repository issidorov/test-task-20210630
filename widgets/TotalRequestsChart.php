<?php


namespace app\widgets;


use app\models\LogFilterForm;
use app\models\LogStat;
use DateTime;
use dosamigos\chartjs\ChartJs;
use yii\base\Widget;

class TotalRequestsChart extends Widget
{
    public LogFilterForm $filter;

    public function run()
    {
        $query = LogStat::find()
            ->andWhere(['system_id' => null])
            ->indexBy('date');

        $this->filter->apply($query, [LogFilterForm::ATTR_DATE]);

        $models = $query->all();

        $data = [];
        $date = $this->filter->begin;
        $datetime = DateTime::createFromFormat('Y-m-d', $date);
        while ($date <= $this->filter->end) {
            $data[$date] = isset($models[$date]) ? $models[$date]->requests_count : 0;
            $datetime->modify('+ 1 day');
            $date = $datetime->format('Y-m-d');
        }

        return ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 150,
                'width' => 300,
            ],
            'data' => [
                'labels' => array_keys($data),
                'datasets' => [
                    [
                        'label' => "Total requests",
                        'cubicInterpolationMode' => 'monotone',
                        'backgroundColor' => "rgba(179,181,198,0.2)",
                        'borderColor' => "rgba(179,181,198,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => array_values($data)
                    ],
                ]
            ]
        ]);
    }
}
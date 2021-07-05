<?php


namespace app\widgets;


use app\models\Browser;
use app\models\Log;
use app\models\LogFilterForm;
use DateTime;
use dosamigos\chartjs\ChartJs;
use yii\base\Widget;

class BrowserRequestsChart extends Widget
{
    public LogFilterForm $filter;

    private $colors = [
        'rgb(255,0,0)',
        'rgb(0,255,0)',
        'rgb(0,0,255)',
    ];

    public function run()
    {
        $datasets = [];
        foreach ($this->getTopBrowserIds() as $index => $browserId) {
            $datasets[] = $this->getBrowserDataset($browserId, Browser::getById($browserId)->name, $this->colors[$index]);
        }

        $dates = [];
        $date = $this->filter->begin;
        $datetime = DateTime::createFromFormat('Y-m-d', $date);
        while ($date <= $this->filter->end) {
            $dates[] = $date;
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
                'labels' => $dates,
                'datasets' => $datasets,
            ]
        ]);
    }

    private function getTopBrowserIds(): array
    {
        return Log::find()
            ->select('browser_id')
            ->andFilterWhere(['system_id' => $this->filter->system])
            ->groupBy('browser_id')
            ->orderBy(['COUNT(*)' => SORT_DESC])
            ->limit(3)
            ->column();
    }

    private function getBrowserDataset($browserId, $label, $color)
    {
        $requests_count = Log::find()
            ->select('COUNT(*)')
            ->andWhere(['>=', 'date', $this->filter->begin])
            ->andWhere(['<=', 'date', $this->filter->end])
            ->andWhere(['browser_id' => $browserId])
            ->andFilterWhere(['system_id' => $this->filter->system])
            ->groupBy('date')
            ->indexBy('date')
            ->column();

        $data = [];
        $date = $this->filter->begin;
        $datetime = DateTime::createFromFormat('Y-m-d', $date);
        while ($date <= $this->filter->end) {
            $data[] = $requests_count[$date] ?? 0;
            $datetime->modify('+ 1 day');
            $date = $datetime->format('Y-m-d');
        }

        return [
            'label' => $label,
            'borderColor' => $color,
            'fill' => false,
            'data' => $data
        ];
    }
}
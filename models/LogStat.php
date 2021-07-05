<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $date
 * @property integer|null $system_id
 * @property integer $requests_count
 * @property string $top_url
 * @property integer $top_browser_id
 *
 * @property Browser $topBrowserId
 */
class LogStat extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'date' => 'Дата',
            'requests_count' => 'Кол-во запросов',
            'top_url' => 'Популярный url',
        ];
    }

    public function getTopBrowser()
    {
        return $this->hasOne(Browser::class, ['id' => 'top_browser_id']);
    }
}
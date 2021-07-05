<?php


namespace app\models;


use app\components\LogParser\LogLineEntity;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

/**
 * @property int $id
 * @property string $ip
 * @property int $time
 * @property string $date
 * @property string $url
 * @property string $system_id
 * @property string $browser_id
 *
 * @property System $system
 * @property Browser $browser
 */
class Log extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => [
                    'system',
                    'browser',
                ],
            ],
        ];
    }

    public function loadFromLogLine(LogLineEntity $line)
    {
        $this->ip = $line->ip;
        $this->time = strtotime($line->time);
        $this->date = date('Y-m-d', $this->time);
        $this->url = StringHelper::truncate(explode('?', $line->url)[0], 995);
        $this->system = System::instanceByName($line->agent->os ?: 'Unknown', true);
        $this->browser = Browser::instanceByName($line->agent->browser ?: 'Unknown', true);
    }

    public function attributeLabels()
    {
        return [
            'ip' => 'IP',
            'time' => 'Время',
            'url' => 'URL',
        ];
    }

    public function getSystem()
    {
        return $this->hasOne(System::class, ['id' => 'system_id']);
    }

    public function getBrowser()
    {
        return $this->hasOne(Browser::class, ['id' => 'browser_id']);
    }
}
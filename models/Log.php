<?php


namespace app\models;


use app\components\LogParser\LogLineEntity;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $ip
 * @property int $time
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
        $this->url = $line->url;
        $this->system = $line->agent->os ? System::instanceByName($line->agent->os, true) : null;
        $this->browser = $line->agent->browser ? Browser::instanceByName($line->agent->browser, true) : null;
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
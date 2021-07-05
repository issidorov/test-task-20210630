<?php


namespace app\components;


use app\models\Log;
use app\models\LogStat;
use Yii;
use yii\db\Query;

/**
 * Сервис для сохранения моделей логов.
 *
 * Пример использования:
 * ```
 * $log1 = new Log();
 * $log2 = new Log();
 * $log3 = new Log();
 *
 * $transaction = Yii::$app->db->beginTransaction();
 *
 * $saver = new LogSaver();
 * $saver->save($log1);
 * $saver->save($log2);
 * $saver->save($log3);
 * $saver->finish();
 *
 * $transaction->commit();
 * ```
 */
class LogSaver
{
    private $dates = [];

    public function save(Log $log, bool $runValidation = true)
    {
        if (!in_array($log->date, $this->dates)) {
            $this->dates[] = $log->date;
        }
        return $log->save($runValidation);
    }

    public function finish()
    {
        LogStat::deleteAll(['date' => $this->dates]);
        $this->insert_stat_without_special_groups();
        $this->insert_stat_grouped_by_system();
    }

    private function insert_stat_without_special_groups()
    {
        $select = (new Query())
            ->select([
                'date',
                'COUNT(*) count',
                '(SELECT t1.url 
                  FROM log t1 
                  WHERE t1.date=log.date
                  GROUP BY t1.url
                  ORDER BY COUNT(*) DESC
                  LIMIT 1
                ) top_url',
                '(SELECT t2.browser_id 
                  FROM log t2 
                  WHERE t2.date=log.date
                  GROUP BY t2.browser_id
                  ORDER BY COUNT(*) DESC
                  LIMIT 1
                ) top_browser_id',
            ])
            ->from('log')
            ->where(['log.date' => $this->dates])
            ->groupBy(['log.date'])
            ->createCommand()
            ->getRawSql();

        $sql = 'INSERT INTO log_stat (date, requests_count, top_url, top_browser_id) ' . $select;
        Yii::$app->db->createCommand($sql)->execute();
    }

    private function insert_stat_grouped_by_system()
    {
        $select = (new Query())
            ->select([
                'date',
                'system_id',
                'COUNT(*) count',
                '(SELECT t1.url 
                  FROM log t1 
                  WHERE t1.date=log.date AND
                        t1.system_id=log.system_id
                  GROUP BY t1.url
                  ORDER BY COUNT(*) DESC
                  LIMIT 1
                ) top_url',
                '(SELECT t2.browser_id 
                  FROM log t2 
                  WHERE t2.date=log.date AND
                        t2.system_id=log.system_id
                  GROUP BY t2.browser_id
                  ORDER BY COUNT(*) DESC
                  LIMIT 1
                ) top_browser_id',
            ])
            ->from('log')
            ->where(['date' => $this->dates])
            ->groupBy(['date', 'system_id'])
            ->createCommand()
            ->getRawSql();

        $sql = 'INSERT INTO log_stat (date, system_id, requests_count, top_url, top_browser_id) ' . $select;
        Yii::$app->db->createCommand($sql)->execute();
    }
}
<?php


namespace app\commands;


use app\components\LogParser\LogParser;
use app\components\LogSaver;
use app\models\Log;
use Error;
use Yii;
use yii\console\Controller;

class LogsController extends Controller
{
    public function actionClean()
    {
        Yii::$app->db->createCommand('TRUNCATE log')->execute();
        Yii::$app->db->createCommand('TRUNCATE log_stat')->execute();
    }

    public function actionImport(string $filename)
    {
        $saver = new LogSaver();
        $parser = new LogParser();
        $lines = $parser->parseFile($filename);

        $transaction = Yii::$app->db->beginTransaction();

        foreach ($lines as $index => $line) {
            $model = new Log();
            $model->loadFromLogLine($line);
            $saver->save($model);
            if (!$model->save()) {
                throw new Error('Save is invalid on line ' . ($index + 1));
            }
        }
        $saver->finish();

        $transaction->commit();
    }
}
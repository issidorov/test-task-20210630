<?php


namespace app\commands;


use app\components\LogParser\LogParser;
use app\components\LogSaver;
use app\models\Log;
use Error;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

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

        Console::startProgress(0, 100);
        $transaction = Yii::$app->db->beginTransaction();

        $index = 0;
        try {
            foreach ($lines as $index => $line) {
                $model = new Log();
                $model->loadFromLogLine($line);
                $saver->save($model);
                if (!$model->save()) {
                    throw new Error('Save is invalid');
                }
                if ($index % 100 === 0) {
                    Console::updateProgress($lines->getPercentPosition(), 100);
                }
            }
            $saver->finish();
        } catch (Throwable $e) {
            Console::error('Import error on line ' . ($index + 1));
            throw $e;
        }

        $transaction->commit();
        Console::updateProgress(100, 100);
        Console::endProgress();
    }
}
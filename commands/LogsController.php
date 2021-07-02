<?php


namespace app\commands;


use app\components\LogParser\LogParser;
use app\models\Log;
use Error;

class LogsController extends \yii\console\Controller
{
    public function actionClean()
    {
        Log::deleteAll([]);
    }

    public function actionImport(string $filename)
    {
        $parser = new LogParser();
        $lines = $parser->parseFile($filename);
        foreach ($lines as $index => $line) {
            $model = new Log();
            $model->loadFromLogLine($line);
            if (!$model->save()) {
                throw new Error('Save is invalid on line ' . ($index + 1));
            }
        }
    }
}
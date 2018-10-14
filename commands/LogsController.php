<?php


namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Logs;

/**
 * Контроллер для записли логов Apache в БД
 */
class LogsController extends Controller
{
    /**
     * Записывает логи в бд
     */
    public function actionIndex($path)
    {
        Logs::writeLogsInDb(Logs::writeLogsInArray($path));
    }

    public function actionTest($path){
        print_r(Logs::writeLogsInArray($path));
    }
}

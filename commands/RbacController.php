<?php


namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Users;

/**
 * Контроллер для создания двух пользователей
 */
class RbacController extends Controller
{
    /**
     * Функция создает пользователей, роли. Присваивает пользователям роли
     * Админ (login:admin,password:admin) и Юзер (login:user,password:user)
     */
    public function actionIndex()
    {
        if(Users::createUsersAndRoles())
            echo "Роли и пользователи успещно созданы!";
        else
            echo "Неизвестная ошибка!";
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id
 * @property string $login Логин
 * @property string $password Пароль
 * @property int $role Тип
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey = "100";
    public $accessToken = "100";

    /**
     * Возвращает имя таблицы с префиксом
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * Правила валидации модели
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['role'], 'integer'],
            [['login'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 255],
            [['login'], 'unique'],
        ];
    }

    /**
     * Лейблы при выводе данных
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'role' => 'Тип',
        ];
    }

    /**
     * Функция создает пользователей, роли. Присваивает пользователям роли
     * Админ (login:admin,password:admin) и Юзер (login:user,password:user)
     */
    public static function createUsersAndRoles(){
        if(!Yii::$app->db->getTableSchema("{{%auth_assignment}}"))
            throw new Exception("Нет базы данных {{%auth_assignment}} скорее всего вы не применили стандартную миграцию yii2 для реализации rbac!");
        if(!Yii::$app->db->getTableSchema("{{%auth_item}}"))
            throw new Exception("Нет базы данных {{%auth_item}} скорее всего вы не применили стандартную миграцию yii2 для реализации rbac!");
        if(!Yii::$app->db->getTableSchema("{{%auth_item_child}}"))
            throw new Exception("Нет базы данных {{%auth_item_child}} скорее всего вы не применили стандартную миграцию yii2 для реализации rbac!");
        if(!Yii::$app->db->getTableSchema("{{%auth_rule}}"))
            throw new Exception("Нет базы данных {{%auth_rule}} скорее всего вы не применили стандартную миграцию yii2 для реализации rbac!");

        //Создаем роль админа
        if(!Yii::$app->authManager->getRole('admin')){
            $role = Yii::$app->authManager->createRole('admin');
            $role->description = 'Администратор';
            Yii::$app->authManager->add($role);
        }
        else
            echo "Роль admin уже существует"."\n";

        //Создаем роль пользователя
        if(!Yii::$app->authManager->getRole('user')){
            $role = Yii::$app->authManager->createRole('user');
            $role->description = 'Пользователь';
            Yii::$app->authManager->add($role);
        }
        else
            echo "Роль user уже существует"."\n";

        $userAdmin = Users::findOne(['login' => 'admin']);
        if(!$userAdmin){
            $userModel = new Users();
            $userModel->login = "admin";
            $userModel->password = md5('admin');
            $userModel->save();
            $userAdmin = Users::findOne(['login' => 'admin']);
        }
        else
            echo "Пользователь с логином admin уже существует, будет использован его id = ".$userAdmin->id."\n";
        
        $checkAdminUser = Yii::$app->authManager->getRolesByUser($userAdmin->id);
        if($checkAdminUser['admin']){
            echo "Пользователю с логином admin уже присвоена роль admin"."\n";
        }
        else
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('admin'), $userAdmin->id);
        

        $justUser = Users::findOne(['login' => 'user']);
        if(!$justUser){
            $justUser = new Users();
            $justUser->login = "user";
            $justUser->password = md5('user');
            $justUser->save();
            $justUser = Users::findOne(['login' => 'user']);
        }
        else
            echo "Пользователь с логином user уже существует, будет использован его id = ".$justUser->id."\n";

        $checkJustUser = Yii::$app->authManager->getRolesByUser($justUser->id);
        if($checkJustUser['user']){
            echo "Пользователю с логином user уже присвоена роль user"."\n";
        }
        else
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $justUser->id);
        

        return true;
    }

    public function validatePassword($pass){
        if($this->password === md5($pass))
            return true;
        return false;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
            $user = Users::findOne(['login' => $username]);
            if($username == "admin")
                $user->accessToken = "1";
            else
                $user->accessToken = "0";
            if ($user != null) {
                return $user;
            }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = Users::findOne(['id' => $id]);
        return isset($user) ? $user : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if($token == 1)
            return Users::findOne(['login' => "admin"]);
        else
            return Users::findOne(['login' => "user"]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return '100';
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
}
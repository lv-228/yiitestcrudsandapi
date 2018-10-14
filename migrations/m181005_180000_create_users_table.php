<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m181005_180000_create_users_table extends Migration
{
    /**
     * Безопасный метод применения миграции
     */
    public function safeUp()
    {
        //Перед выполнением миграции проверяем существует ли уже таблица с идентичным названием
        if(Yii::$app->db->getTableSchema("{{%users}}"))
            throw new Exception("База данных {{%users}} уже существует!");
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string(100)->notNull()->unique()->comment('Логин'),
            'password' => $this->string()->notNull()->comment('Пароль'),
            'role' => $this->boolean()->comment('Тип'),
        ]);
    }

    /**
     * Откат миграции
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}

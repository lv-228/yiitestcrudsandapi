<?php

use yii\db\Migration;
/**
 * Handles the creation of table `logs`.
 */
class m181005_180001_create_logs_table extends Migration
{
    /**
     * Безопасный метод применения миграции
     */
    public function safeUp()
    {
        //Перед выполнением миграции проверяем существует ли уже таблица с идентичным названием
        if(Yii::$app->db->getTableSchema("{{%logs}}"))
            throw new Exception("База данных {{%logs}} уже существует!");
        $this->createTable('logs', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(20)->comment("Айпи с которого пришел запрос")->notNull(),
            'data_time' => $this->dateTime()->comment("Дата и время запроса")->notNull(),
            'req' => $this->string()->comment("Тело запроса")->notNull(),
            'res' => $this->string()->comment("Тело ответа")->notNull(),
        ]);
    }

    /**
     * Откат миграции
     */
    public function safeDown()
    {
        $this->dropTable('logs');
    }
}

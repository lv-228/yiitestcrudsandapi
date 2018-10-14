<?php

use yii\db\Migration;

/**
 * Handles adding type to table `logs`.
 */
class m181012_084738_add_type_column_to_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('logs', 'type', $this->string(10)->comment("Тип запроса")->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('logs', 'type');
    }
}

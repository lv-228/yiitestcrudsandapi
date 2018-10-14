<?php

use yii\db\Migration;

/**
 * Handles adding code to table `logs`.
 */
class m181012_115150_add_code_column_to_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('logs', 'code', $this->integer(3)->comment("Код ответа")->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('logs', 'code');
    }
}

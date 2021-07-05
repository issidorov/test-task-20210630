<?php

use yii\db\Migration;

/**
 * Class m210703_104639_create_table_log_stat
 */
class m210703_104639_create_table_log_stat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_stat', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'system_id' => $this->integer(),
            'requests_count' => $this->integer(),
            'top_url' => $this->string(1000),
            'top_browser_id' => $this->integer(),
        ]);

        $this->addForeignKey('log_stat__system_id__fk', 'log_stat', 'system_id', 'system', 'id');
        $this->addForeignKey('log_stat__top_browser_id__fk', 'log_stat', 'top_browser_id', 'browser', 'id');

        $this->createIndex('log_stat__date__index', 'log_stat', 'date');
        $this->createIndex('log_stat__system_id__index', 'log_stat', 'system_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_stat');
    }
}

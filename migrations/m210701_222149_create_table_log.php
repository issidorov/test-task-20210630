<?php

use yii\db\Migration;

/**
 * Class m210701_222149_create_table_log
 */
class m210701_222149_create_table_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(),
            'time' => $this->integer(),
            'date' => $this->date(),
            'url' => $this->string(1000),
            'system_id' => $this->integer(),
            'browser_id' => $this->integer(),
        ]);
        $this->createTable('system', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
        $this->createTable('browser', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('log__system_id__fk', 'log', 'system_id', 'system', 'id', 'RESTRICT');
        $this->addForeignKey('log__browser_id__fk', 'log', 'browser_id', 'browser', 'id', 'RESTRICT');

        $this->createIndex('log__ip__index', 'log', 'ip');
        $this->createIndex('log__time__index', 'log', 'time');
        $this->createIndex('log__date__index', 'log', 'date');
        $this->createIndex('log__system_id__index', 'log', 'system_id');
        $this->createIndex('log__browser_id__index', 'log', 'browser_id');

        $this->insert('system', ['name' => 'Unknown']);
        $this->insert('browser', ['name' => 'Unknown']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log');
        $this->dropTable('system');
        $this->dropTable('browser');
    }
}

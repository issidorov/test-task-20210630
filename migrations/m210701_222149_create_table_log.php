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

        $this->addForeignKey('fk__log__system', 'log', 'system_id', 'system', 'id', 'RESTRICT');
        $this->addForeignKey('fk__log__browser', 'log', 'browser_id', 'browser', 'id', 'RESTRICT');

        $this->createIndex('log__ip', 'log', 'ip');
        $this->createIndex('log__time', 'log', 'time');
        $this->createIndex('log__url', 'log', 'url');
        $this->createIndex('log__os_id', 'log', 'system_id');
        $this->createIndex('log__browser_id', 'log', 'browser_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__log__system', 'log');
        $this->dropForeignKey('fk__log__browser', 'log');
        $this->dropTable('log');
        $this->dropTable('system');
        $this->dropTable('browser');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m180821_015906_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'name' => $this->string()->notNull()->unique(),
            'order_count' => $this->double()->defaultValue(0),
            'use_money' => $this->double()->defaultValue(0),
            'money' => $this->double()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}

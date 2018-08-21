<?php

use yii\db\Migration;

/**
 * Handles the creation of table `items`.
 */
class m180821_015934_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('items', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'price' => $this->double()->defaultValue(0),
            'num' => $this->integer()->defaultValue(0),
            'sell_count' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('items');
    }
}

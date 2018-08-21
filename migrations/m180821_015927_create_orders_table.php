<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m180821_015927_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'item_count' => $this->integer()->defaultValue(0),
            'total' => $this->double()->defaultValue(0),
            'order_code' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);

        $this->addForeignKey(
            'fk-orders-user_id',
            'orders',
            'user_id',
            'users',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-orders-user_id',
            'orders'
        );

        $this->dropTable('orders');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_item`.
 */
class m180821_015948_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_item', [
            'order_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-order_item-order_id',
            'order_item',
            'order_id'
        );
        $this->createIndex(
            'idx-order_item-item_id',
            'order_item',
            'item_id'
        );

        $this->addForeignKey(
            'fk-order_item-user_id',
            'order_item',
            'order_id',
            'orders',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-order_item-user_id',
            'order_item'
        );

        $this->dropTable('order_item');
    }
}

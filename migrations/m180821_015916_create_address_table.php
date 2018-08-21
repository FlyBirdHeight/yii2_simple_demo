<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180821_015916_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'consignee' => $this->string()->notNull(),
            'code' => $this->string(),
            'residence' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'default' => $this->integer()->defaultValue(0)
        ]);

        $this->addForeignKey(
            'fk-address-user_id',
            'address',
            'user_id',
            'users',
            'id',
            'CASCADE'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-address-user_id',
            'address'
        );

        $this->dropTable('address');
    }
}

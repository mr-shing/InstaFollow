<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m240227_084024_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'follow_request_count' => $this->integer()->notNull(),
            'filled_count' => $this->integer()->defaultValue(0)
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-orders-user_id}}',
            '{{%orders}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-orders-user_id}}',
            '{{%orders}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-orders-user_id}}',
            '{{%orders}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-orders-user_id}}',
            '{{%orders}}'
        );

        $this->dropTable('{{%orders}}');
    }
}

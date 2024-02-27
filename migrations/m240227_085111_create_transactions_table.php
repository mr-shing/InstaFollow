<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m240227_085111_create_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'coin_count' => $this->integer()->notNull(),
            'model_class' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-transactions-user_id}}',
            '{{%transactions}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-transactions-user_id}}',
            '{{%transactions}}',
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
            '{{%fk-transactions-user_id}}',
            '{{%transactions}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-transactions-user_id}}',
            '{{%transactions}}'
        );

        $this->dropTable('{{%transactions}}');
    }
}

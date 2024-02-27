<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%insta_follow}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m240227_083350_create_insta_follow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%insta_follow}}', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer()->notNull(),
            'follow_by' => $this->integer()->notNull(),
        ]);

        // creates index for column `owner_id`
        $this->createIndex(
            '{{%idx-insta_follow-owner_id}}',
            '{{%insta_follow}}',
            'owner_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-insta_follow-owner_id}}',
            '{{%insta_follow}}',
            'owner_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `follow_by`
        $this->createIndex(
            '{{%idx-insta_follow-follow_by}}',
            '{{%insta_follow}}',
            'follow_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-insta_follow-follow_by}}',
            '{{%insta_follow}}',
            'follow_by',
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
            '{{%fk-insta_follow-owner_id}}',
            '{{%insta_follow}}'
        );

        // drops index for column `owner_id`
        $this->dropIndex(
            '{{%idx-insta_follow-owner_id}}',
            '{{%insta_follow}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-insta_follow-follow_by}}',
            '{{%insta_follow}}'
        );

        // drops index for column `follow_by`
        $this->dropIndex(
            '{{%idx-insta_follow-follow_by}}',
            '{{%insta_follow}}'
        );

        $this->dropTable('{{%insta_follow}}');
    }
}

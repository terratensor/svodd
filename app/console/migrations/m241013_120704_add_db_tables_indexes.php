<?php

use yii\db\Migration;

/**
 * Class m241013_120704_add_db_tables_indexes
 */
class m241013_120704_add_db_tables_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-questions-parent_data_id', '{{%questions}}', 'parent_data_id');
        $this->createIndex('idx-comments_bookmarks-user_id', '{{%comment_bookmarks}}', 'user_id');
        $this->createIndex('idx-comments_bookmarks-comment_id', '{{%comment_bookmarks}}', 'comment_id');

        $this->createIndex('idx-svodd_chart_data-question_id', '{{%svodd_chart_data}}', 'question_id');
        $this->createIndex('idx-svodd_chart_data-topic_number', '{{%svodd_chart_data}}', 'topic_number');

        $this->createIndex('idx-tg_comments_messages-creted_at', '{{%tg_comments_messages}}', 'created_at');

        $this->createIndex('idx-question_stats-last_comment_date', '{{%question_stats}}', 'last_comment_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-questions-parent_data_id', '{{%questions}}');
        $this->dropIndex('idx-comments_bookmarks-user_id', '{{%comment_bookmarks}}');
        $this->dropIndex('idx-comments_bookmarks-comment_id', '{{%comment_bookmarks}}');

        $this->dropIndex('idx-svodd_chart_data-question_id', '{{%svodd_chart_data}}');
        $this->dropIndex('idx-svodd_chart_data-topic_number', '{{%svodd_chart_data}}');

        $this->dropIndex('idx-tg_comments_messages-creted_at', '{{%tg_comments_messages}}');

        $this->dropIndex('idx-question_stats-last_comment_date', '{{%question_stats}}');
    }
}

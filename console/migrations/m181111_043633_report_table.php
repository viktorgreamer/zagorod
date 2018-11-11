<?php

use yii\db\Migration;

/**
 * Class m181111_043633_report_table
 */
class m181111_043633_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->createTable('report_table',[
            'table_id' => \yii\db\Schema::TYPE_INTEGER,
            'name' => \yii\db\Schema::TYPE_STRING,
            'event_id' => \yii\db\Schema::TYPE_INTEGER]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181111_043633_report_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181111_043633_report_table cannot be reverted.\n";

        return false;
    }
    */
}

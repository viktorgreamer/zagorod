<?php

use yii\db\Migration;

/**
 * Class m181111_044252_udate_report_table
 */
class m181111_044252_udate_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->update('report_table',[
            'table_id' => $this->primaryKey(5)->notNull()->comment('Таблица')." AUTO_INCREMENT",
            'name' => $this->string(256)->comment('Название'),
            'event_id' => $this->integer(5)->null()->comment('Событие')]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181111_044252_udate_report_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181111_044252_udate_report_table cannot be reverted.\n";

        return false;
    }
    */
}

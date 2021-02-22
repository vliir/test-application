<?php

use yii\db\Migration;

/**
 * Class m210220_090931_add_table_apples
 */
class m210220_090931_add_table_apples extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'state' => $this->text()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%apples}}');
    }
}

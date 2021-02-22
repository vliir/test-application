<?php

use yii\db\Migration;

/**
 * Class m210220_082826_add_user_admin
 */
class m210220_082826_add_user_admin extends Migration
{
    public function up()
    {
        try {
        $this->insert('{{%user}}', [
            'id' => '',
            'username' => 'admin',
            'auth_key' => 'key-100',
            'password_hash' => '$2y$13$CBScsg1eHtUQZyOK8fJnPu2Rx1nM81wje75B.NygdC56wLqVeASlm',
            'password_reset_token' => '123',
            'email' => 'admin@admin.my',

            'status' => '10',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        } catch(\Exception $e) {
            echo "m210220_082826_add_user_admin cannot create, user admin already exists.\n";
        }
    }

    public function down()
    {
        echo "m210220_082826_add_user_admin cannot be reverted.\n";

        return false;
    }
}

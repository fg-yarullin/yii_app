<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m220528_100909_user
 */
class m220528_100909_user extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'surname' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(),
            'activation_key' => $this->string(),
            'is_active' => $this->boolean()->defaultValue(false),
            'created' => $this->dateTime()->defaultValue(new Expression('NOW()')),
        ]);
        $this->createIndex(
            '{{%idx-unique-user-email}}',
            '{{%user}}',
            ['email',],
            true
        );
    }

    public function down()
    {
        $this->dropTable('user');
    }
}

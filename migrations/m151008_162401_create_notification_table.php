<?php

use yii\db\Migration;

class m151008_162401_create_notification_table extends Migration
{
    const TABLE_NAME = '{{%notification}}';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'keyId' => $this->integer(),
            'type' => $this->string()->notNull(),
            'userType' => $this->string()->notNull(),
            'userId' => $this->integer()->notNull(),
            'seen' => $this->boolean()->notNull(),
            'createTime' => $this->integer(20)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}

<?php

use yii\db\Migration;

class m160921_171124_alter_notification_table extends Migration
{
    const TABLE_NAME = '{{%notification}}';

    public function up()
    {
        $this->alterColumn(self::TABLE_NAME, 'keyId', $this->string());
    }

    public function down()
    {
        $this->alterColumn(self::TABLE_NAME, 'keyId', $this->integer());
    }
}

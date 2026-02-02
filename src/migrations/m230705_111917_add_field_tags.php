<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m230705_111917_add_field_tags extends \yii\db\Migration
{

    public function safeUp()
    {
       $this->addColumn('tag','soft_delete', $this->dateTime()->null()->defaultValue(null)->after('child_allowed'));
    }

    public function safeDown()
    {
        $this->dropColumn('tag','soft_delete');
    }
}
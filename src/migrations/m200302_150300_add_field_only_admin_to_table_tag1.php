<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;


class m200302_150300_add_field_only_admin_to_table_tag1 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->addColumn('tag', 'only_admin', $this->boolean()->defaultValue(0)->after('visible'));
        }catch (\yii\db\Exception $e){

        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('tag', 'only_admin');
    }
}
<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m200122_094415_create_root_nework extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('tag', [
            'lft' => 1,
            'rgt' => 2,
            'lvl' => 0,
            'nome' =>'Network',
            'codice' => 'root_networks',
            'icon_type' => 1,
            'icon' => '',
            'active' => 1,
            'selected' => 0,
            'disabled' => 0,
            'readonly' => 0,
            'visible' => 1,
            'collapsed' => 0,
            'movable_u' => 1,
            'movable_d' => 1,
            'movable_l' => 1,
            'movable_r' => 1,
            'removable' => 1,
            'removable_all' => 0,
            'child_allowed' => 1,

        ]);

        $this->execute("UPDATE tag SET root = tag.id WHERE tag.codice= 'root_networks'");

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        \open20\amos\tag\models\Tag::deleteAll([
            'codice' => 'root_networks'
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}

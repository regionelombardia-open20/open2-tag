<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\migrations
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
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

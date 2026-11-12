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
class m200211_093715_add_indexes extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $this->createIndex('idx_entitys_tags_mm_classname_id_record_id', 'entitys_tags_mm', ['models_classname_id', 'record_id']);
        $this->createIndex('idx_entitys_tags_mm_record_id', 'entitys_tags_mm', 'record_id');
        $this->addForeignKey('fk_entitys_tags_mm_tag_id', 'entitys_tags_mm', 'tag_id', 'tag', 'id');

        $this->createIndex('idx_tag_network_record_id', 'tag', 'network_record_id');
        $this->createIndex('idx_tag_cwh_config_id', 'tag', 'cwh_config_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropForeignKey('fk_entitys_tags_mm_tag_id', 'entitys_tags_mm');
        $this->dropIndex('idx_entitys_tags_mm_classname_id_record_id', 'entitys_tags_mm');
        $this->dropIndex('idx_entitys_tags_mm_record_id', 'entitys_tags_mm');
        $this->dropIndex('idx_tag_network_record_id', 'tag');
        $this->dropIndex('idx_tag_cwh_config_id', 'tag');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}

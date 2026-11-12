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

use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m200121_175715_add_columns_network extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->addColumn('tag', 'network_record_id', $this->integer()->defaultValue(null)->after('descrizione'));

        $this->addColumn('tag', 'cwh_config_id', $this->integer()->defaultValue(null)->after('descrizione'));
        $this->addColumn('tag', 'is_network', $this->integer(1)->defaultValue(0)->after('descrizione'));
        } catch (\yii\db\Exception $e) {
        } catch (Exception $e) {
        }
        $this->addColumn('entitys_tags_mm', 'models_classname_id', $this->integer()->defaultValue(null)->after('entitys_tags_mm_id'));
        $this->addForeignKey('fk_entitys_tags_mm_classname_id1', 'entitys_tags_mm', 'models_classname_id', 'models_classname', 'id');

        $this->execute(
            "UPDATE entitys_tags_mm
                 inner join models_classname on (entitys_tags_mm.classname = models_classname.classname)
                 set entitys_tags_mm.models_classname_id = models_classname.id"
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropColumn('tag', 'cwh_config_id');
        $this->dropColumn('tag', 'is_network');
        $this->dropColumn('tag', 'network_record_id');
        $this->dropForeignKey('fk_entitys_tags_mm_classname_id1', 'entitys_tags_mm');
        $this->dropColumn('entitys_tags_mm', 'models_classname_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}
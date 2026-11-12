<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\tag
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

class m160919_091930_tag_models_auth_items_mm extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = \open20\amos\tag\models\TagModelsAuthItemsMm::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        $this->createTable($this->tabella, [
            'tag_id' => $this->integer(11)->notNull()->comment('Root'),
            'classname' => $this->string(255)->notNull()->comment('Model'),
            'auth_item' => $this->string(255)->notNull()->comment('Item'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        $this->addPrimaryKey('pk_tag_models_0', $this->tabella, 'tag_id, classname, auth_item');

        $this->addForeignKey('fk_roots01', $this->tabella, 'tag_id', 'tag', 'id');
        $this->addForeignKey('fk_auth_item01', $this->tabella, 'auth_item', 'auth_item', 'name');
        // remove unused tables
        $tableSchema = Yii::$app->db->schema->getTableSchema('{{%tag_auth_item_mm}}');
        if(!empty($tableSchema)) {
            $this->dropTable('{{%tag_auth_item_mm}}');
        }
        $tableSchema = Yii::$app->db->schema->getTableSchema('{{%tag_models_mm}}');
        if(!empty($tableSchema)) {
            $this->dropTable('{{%tag_models_mm}}');
        }
        return true;
    }

    public function safeDown()
    {
        $this->dropTable($this->tabella);
        return true;
    }
}

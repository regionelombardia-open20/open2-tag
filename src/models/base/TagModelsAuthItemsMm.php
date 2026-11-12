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

namespace open20\amos\tag\models\base;

use open20\amos\core\record\AmosRecordAudit;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
use mdm\admin\models\AuthItem;

/**
 * This is the base-model class for table "tag_models_auth_items_mm".
 *
 * @property integer $tag_id
 * @property string $classname
 * @property string $auth_item
 *
 * @property Tag $tag
 * @property AuthItem $authItem
 */
class TagModelsAuthItemsMm extends AmosRecordAudit
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag_models_auth_items_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'classname', 'auth_item'], 'required'],
            [['tag_id'], 'integer'],
            [['classname', 'auth_item'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => AmosTag::t('amostag', 'Root'),
            'classname' => AmosTag::t('amostag', 'Model'),
            'auth_item' => AmosTag::t('amostag', 'Ruolo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(
            Tag::class,
            ['id' => 'tag_id']
        )
        ->inverseOf('tagModelsAuthItemsMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItem()
    {
        return $this->hasOne(
            AuthItem::class,
            ['name' => 'auth_item']
        )
        ->inverseOf('tagModelsAuthItemsMms');
    }

}
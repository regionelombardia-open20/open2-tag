<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

namespace lispa\amos\tag\models\base;

use lispa\amos\core\record\Record;
use lispa\amos\tag\AmosTag;

/**
 * This is the base-model class for table "tag".
 *
 * @property integer $entitys_tags_mm_id
 * @property string $classname
 * @property integer $record_id
 * @property integer $tag_id
 * @property integer $root_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \lispa\amos\tag\models\Tag $tag
 */
class BaseEntitysTagsMm extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entitys_tags_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entitys_tags_mm_id', 'record_id', 'tag_id', 'root_id'], 'integer'],
            [['classname'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entitys_tags_mm_id' => AmosTag::t('amostag', 'ID'),
            'record_id' => AmosTag::t('amostag', 'ID record'),
            'tag_id' => AmosTag::t('amostag', 'ID tag'),
            'created_at' => AmosTag::t('amostag', 'Creato il'),
            'updated_at' => AmosTag::t('amostag', 'Aggiornato il'),
            'deleted_at' => AmosTag::t('amostag', 'Cancellato il'),
            'created_by' => AmosTag::t('amostag', 'Creato da'),
            'updated_by' => AmosTag::t('amostag', 'Aggiornato da'),
            'deleted_by' => AmosTag::t('amostag', 'Cancellato da'),
            'version' => AmosTag::t('amostag', 'Versione numero'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(\lispa\amos\tag\models\Tag::className(), ['id' => 'tag_id']);
    }
}

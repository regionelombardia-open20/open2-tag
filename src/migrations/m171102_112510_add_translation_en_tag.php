<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

class m171102_112510_add_translation_en_tag extends \yii\db\Migration
{

    public function safeUp()
    {

        $this->addColumn(\lispa\amos\tag\models\Tag::tableName(), 'nome_en',
            $this->text()
                ->null()
                ->after('nome')
        );

        $this->addColumn(\lispa\amos\tag\models\Tag::tableName(), 'descrizione_en',
            $this->string(255)
                ->null()
                ->after('descrizione')
        );

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(\lispa\amos\tag\models\Tag::tableName(), 'nome_en');
        $this->dropColumn(\lispa\amos\tag\models\Tag::tableName(), 'descrizione_en');

        return true;
    }
}

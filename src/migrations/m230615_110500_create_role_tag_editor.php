<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m230615_110500_create_role_tag_editor extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'TAG_EDITOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo con permesso di CREATE e UPDATE sui tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG'],
                'children' => [
                    'TAG_CREATE',
                    'TAG_UPDATE',
                    'TAG_READ',
                    \open20\amos\tag\widgets\icons\WidgetIconTag::className(),
                    \open20\amos\tag\widgets\icons\WidgetIconTagManager::className(),
                ]
            ],

        ];
    }
}

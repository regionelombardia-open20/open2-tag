<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m241111_100500_create_role_tag_manager_light extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'TAG_MANAGER_LIGHT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo con permesso di CREATE e UPDATE sui tag',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TAG','TAG_EDITOR'],
                'children' => [
                    'TAG_CREATE',
                    'TAG_UPDATE',
                    'TAG_READ',
                ]
            ],

        ];
    }
}

<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170414_081920_add_amministratore_tag_to_admin
 */
class m170414_081920_add_amministratore_tag_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_TAG',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore tag',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}

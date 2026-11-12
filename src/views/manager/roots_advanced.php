<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\tag\view\manager
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

use open20\amos\admin\AmosAdmin;
use open20\amos\tag\AmosTag;
use kartik\select2\Select2;

/** @var \yii\web\View $this */
/** @var \kartik\form\ActiveForm $form */
/** @var \open20\amos\tag\models\Tag $node */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();
$userProfileClassname = $adminModule->model('UserProfile');

if ($node->isRoot()):
    $moduliTaggabili = [];
    /** @var \open20\amos\core\module\AmosModule $module */
    $moduleTag = \Yii::$app->getModule(AmosTag::getModuleName());
    foreach ($moduleTag->modelsEnabled as $module) {
        $function = new \ReflectionClass($module);
        $moduliTaggabili[$module] = $function->getShortName();
    }
    $ruoliDaScegliere = [];
    foreach (Yii::$app->getAuthManager()->getRoles() as $key => $ruolo) {
        $ruoliDaScegliere[$key] = $ruolo->name;
    }

    /**
     * TODO
     * Attenzione: integrare la select2 nel model $node, così va bene
     * ma non benissimo...
     */


    $i =0;
    foreach ($moduliTaggabili as $keyModule => $moduleName): ?>
        <div class="row">
            <div class="col-sm-6">
                <h4><?= AmosTag::tHtml('amostag','Abilita questa root per: ') . $moduleName ?></h4>
            </div>
            <div class="col-sm-12">
                <div class="checkbox">
                <?= Select2::widget([
                    'name' => 'ModelsRoles[' . $keyModule . ']',
                    'value' => $node->getAssignedRolesByClassname($keyModule),
                    'data' => $ruoliDaScegliere,
                    'options' => [
                        'placeholder' => AmosTag::t('amostag','Seleziona un ruolo...'),
                        'multiple' => true
                    ],
                    'id' => 'roleSelect'. $i ,
                    'pluginOptions' => [
                        'tags' => true,
                        'maximumInputLength' => 50
                    ],
                ])
                ?>
                </div>
            </div>
        </div>

        <?php
    $i++;
    endforeach;
    ?>
    <?php if (Yii::$app->getModule('cwh')): ?>

    <div class="row">
        <div class="col-sm-6">
            <h4><?= AmosTag::tHtml('amostag',"&Egrave; un albero per aree di interesse dell'utente?") ?></h4>
        </div>
        <div class="col-sm-12">
            <div class="checkbox">
            <?= Select2::widget([
                'name' => 'CwhTagInterestMm[' . $userProfileClassname . ']',
                'value' => $node->getAssignedInterestByClassname($userProfileClassname),
                'data' => $ruoliDaScegliere,
                'options' => [
                    'placeholder' => AmosTag::t('amostag', 'Seleziona un ruolo...'),
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 50
                ],
            ])
            ?>
            </div>
        </div>
    </div>

    <?php endif; ?>

<?php endif; ?>

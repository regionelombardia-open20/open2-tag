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

use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\tag\models\Tag $node
 */

// parse parent key
if ($noNodesMessage) {
    $parentKey = '';
} elseif (empty($parentKey)) {
    $parent = $node->parents(1)->one();
    $parentKey = empty($parent) ? '' : Html::getAttributeValue($parent, $keyAttribute);
}

$isAdmin = Yii::$app->user->can('AMMINISTRATORE_TAG');
?>

<style>
    form .form-group .form-control[disabled], form .form-group .form-control[readonly] {
        background-color: #eeeeee !important;
    }
</style>
<div class="row">
    <?php if ($isAdmin) { ?>
        <?php if ($node->isRoot() || strpos($parentKey, "ROOT") !== false) { ?>
            <div class="col-sm-6">
                <?php echo $form->field($node, 'limit_selected_tag')->textInput(); ?>
            </div>
        <?php } ?>
        <div class="col-sm-6">
            <?php echo $form->field($node, 'codice')->textInput(); ?>
        </div>
    <?php } ?>
    <div class="col-sm-12">
        <?php echo $form->field($node, 'descrizione')->textarea([
            'rows' => 6,
            'disabled' => !$isAdmin && $node->only_admin
        ]); ?>
    </div>
</div>

<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
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

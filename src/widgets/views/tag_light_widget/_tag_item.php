<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-tag
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

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use \open20\amos\tag\AmosTag;


/**
 * @var  $model
 * @var  $b
 * @var  $completeCollapseId
 * @var  $tags_selected_ids
 * @var  $viewParams
 *
 **/
$model = $b['model'];
$path = $model['path'];

$tags_selected_ids = $viewParams['tags_selected_ids'];
$collapseTree = $viewParams['treeCollapsed'];
$occurrencies = $viewParams['occurrenciesTags'];
$labelContent = $viewParams['labelContent'];
$selectSonsOnly = $viewParams['selectSonsOnly'];

$isParent = !empty($b['children']);

$iconTag = $isParent ? 'mdi mdi-palette-swatch' : 'mdi mdi-tag';
$showCheckBox = true;
if ($selectSonsOnly) {
    if (!empty($b['children'])) {
        $showCheckBox = false;
    }
}
$checked = false;
if (!empty($tags_selected_ids[$model['root']])) {
    $checked = in_array($model['id'], $tags_selected_ids[$model['root']]);
}
$collapseClass = $collapseTree ? 'collapsed' : '';
$ariaExpanded = $collapseTree ? 'false' : 'true';

$nOccurrencies = 0;
if (!empty($occurrencies[$model['id']])) {
    $nOccurrencies = $occurrencies[$model['id']];
}

$stringPath = '/ ' . implode(' / ', $path);
?>

<div class="item-tree <?= ($checked ? 'item-tree-checked' : '') ?>" id="tree_light_tag_item_<?= $model['id'] ?>">
    <div class="d-flex align-items-baseline">
        <?php if ($showCheckBox) { ?>
            <div class="btn-checkbox">
                <?= \yii\helpers\Html::checkbox('tag_value_' . $model['id'], $checked, [
                    'data-root' => $model['root'],
                    'data-key' => $model['id'],
                    'class' => 'item_tag_id'
                ]) ?>
            </div>
        <?php } ?>

        <?php if ($isParent) { ?>
            <a href="#<?= $completeCollapseId ?>" class="link-tag <?= $collapseClass ?>" data-toggle="collapse" aria-controls="<?= $completeCollapseId ?>" aria-expanded="<?= $ariaExpanded ?>">
            <?php } else { ?>
                <div class="link-tag">
                <?php } ?>

                <div class="title-item-tree">
                    <span class="icon-tag <?= $iconTag ?>"></span><span class="name-tag"><?= $model['nome'] ?></span>
                </div>

                <div class="path-tag" style="display:none">
                    <?= $stringPath ?>
                </div>

                <div style="display:none" class="tag-is-checked text-primary" title="<?= AmosTag::t('amostag', "Tag selezionati") ?>"></div>
                <div class="tag-assegnati" title="<?= AmosTag::t('amostag', "Ci sono {x} {label} assegnati a questo tag", [
                                                        'x' => $nOccurrencies,
                                                        'label' => $labelContent
                                                    ]) ?>">
                    <span class="mdi mdi-bookmark-outline"></span> <?= $nOccurrencies ?>
                </div>

                <?php if ($isParent) { ?>
                    <div class="arrow-accordion m-l-5">
                        <span class="mdi mdi-chevron-down"></span>
                    </div>
                <?php } ?>


                <?php if ($isParent) { ?>
            </a>
        <?php } else { ?>
    </div>
<?php } ?>
</div>

</div>
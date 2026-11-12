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

/**
 * @var $model
 * @var $prev
 * @var $next
 * @var $parent_id
 * @var $nOccurrencies
 */

use open20\amos\tag\utility\TagUtility;
use open20\amos\core\helpers\Html;
use open20\amos\tag\AmosTag;

$children = TagUtility::getChildrensForTag($model);
$labelContent = AmosTag::t('amostag', "Contenuti");
$nOccurrencies = 0;
if (!empty($occurrencies[$model->id])) {
    $nOccurrencies = $occurrencies[$model->id];
}
?>

<div class="li-link-list ">
    <div class="item-tree item-tree-manage <?= $model->disabled ? 'tag-hidden' : ''  ?>">
        <div class="d-flex align-items-center">
            <div class="btn-arrows-order">
                <?php if ($prev) { ?>
                    <a class="prev-btn" data-current-id="<?= $model->id ?>" data-prev-id="<?= $prev ?>" data-parent-id="<?= $parent_id ?>">
                        <span class="mdi mdi-arrow-up-thin"></span>
                    </a>
                <?php } ?>

                <?php if ($next) { ?>
                    <a class="next-btn" data-current-id="<?= $model->id ?>" data-next-id="<?= $next ?>" data-parent-id="<?= $parent_id ?>">
                        <span class="mdi mdi-arrow-down-thin"></span>
                    </a>
                <?php } ?>
            </div>

            <div class="btn-checkbox">
                <?= Html::checkbox('tag_array[]', false, ['value' => $model->id, 'class' => 'model-checkbox']) ?>
            </div>

            <?php
            $titleAssegnati = AmosTag::t('amostag', "Ci sono {x} {label} assegnati a questo tag", [
                'x' => $nOccurrencies,
                'label' => $labelContent
            ]);

            if (count($children) > 0) { ?>
                <a href="javascript:void(0)" data-tag="<?= $model->id ?>"
                    class="link-tag explore-tag" title="<?= AmosTag::t('amostag', "Mostra tag figli") ?>">
                    <div class="title-item-tree">
                        <span class="icon-tag mdi mdi-palette-swatch"></span><span class="name-tag"><?= $model->nome ?></span>
                    </div>
                    <?php if ($model->disabled) { ?>
                        <span class="mdi mdi-eye-off mdi-18px m-l-5"></span>
                    <?php } ?>
                    <?php if ($model->readonly) { ?>
                        <span class="mdi mdi-eye-off mdi-18px m-l-5"></span>
                    <?php } ?>
                    <?php if ($nOccurrencies > 0) { ?>
                        <div class="tag-assegnati" title="<?= $titleAssegnati ?>">
                            <span class="mdi mdi-bookmark-outline"></span><?= $nOccurrencies ?>
                        </div>
                    <?php } ?>
                    <span class="mdi mdi-menu-right mdi-24px"></span>
                </a>

            <?php } else { ?>
                <div class="link-tag">
                    <div class="title-item-tree">
                        <span class="icon-tag mdi mdi mdi-tag "></span><span class="name-tag"><?= $model->nome ?></span>
                    </div>
                    <?php if ($model->disabled) { ?>
                        <span class="mdi mdi-eye-off mdi-18px m-l-5"></span>
                    <?php } ?>
                    <?php if ($model->readonly) { ?>
                        <span class="mdi mdi-lock-outline text-secondary mdi-18px m-l-5"></span>
                    <?php } ?>
                    <?php if ($nOccurrencies > 0) { ?>
                        <div class="tag-assegnati" title="<?= $titleAssegnati ?>">
                            <span class="mdi mdi-bookmark-outline"></span><?= $nOccurrencies ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="btn-group dropdown wrapper-dropdown-other-plugin">
                <button class="btn btn-link btn-xs btn-other-menu dropdown-toggle" type="button"
                    id="dropdownOtherPluginMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    title="<?= AmosTag::t('amostag', 'Gestisci') ?>">
                    <span class="mdi mdi-dots-vertical text-secondary mdi-18px"></span>
                </button>

                <ul class="list-menu-dropdown dropdown-menu dropdown-menu-right">
                    <li>
                        <?=
                        \yii\helpers\Html::a(
                            AmosTag::t('amostag', 'Modifica'),
                            "#",
                            [
                                'class' => ' float-right mr-2',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalUpdateTag',
                                'data-id' => $model->id,
                                'id' => 'linkUpdateTag-' . $model->id,
                            ]
                        );
                        ?>
                    </li>
                    <li>
                        <?php
                        echo \yii\helpers\Html::a(
                            AmosTag::t('amostag', 'Crea nuovo tag figlio'),
                            "#",
                            [
                                'class' => ' float-right mr-2',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalCreaNuovoTag',
                                'data-id' => $model->id,
                                'id' => 'linkCreaNuovoTag-' . $model->id,
                            ]
                        );
                        ?>
                    </li>
                    <li>
                        <?php
                        echo \yii\helpers\Html::a(
                            AmosTag::t('amostag', $model->disabled ? AmosTag::t('amostag', 'Mostra') : AmosTag::t('amostag', 'Nascondi')),
                            "#",
                            [
                                'class' => $model->disabled ? '' : 'text-danger',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalHideShowTag',
                                'data-id' => $model->id,
                                'data-disabled' => $model->disabled,
                                'id' => 'linkHideShowTag-' . $model->id,
                            ]
                        );
                        ?>
                </ul>

            </div>
        </div>


    </div>
</div>
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
 * @var $model \open20\amos\tag\models\Tag
 * @var $folderList
 * @var $folderCount
 * @var $occurrencies
 * @var $children
 * @var $lvl
 */
use open20\amos\tag\AmosTag;
use \yii\bootstrap\Modal;


$associations = [];
foreach ($children as $index => $child) {
    $previous = $index > 0 ? $children[$index - 1] : null;
    $next = $index < count($children) - 1 ? $children[$index + 1] : null;
    $associations[$child->id] = [
        'prev' => $previous ? $previous->id : null,
        'next' => $next ? $next->id : null,
    ];
}
?>


<div class="navigation-manage-tag">
    <div style="display: none" class="loader-3dots">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="parent-tag-vertical">
        <?php $count = 1; ?>
        <?php foreach ($folderList as $folder) {
            if ($count < $folderCount) {?>
                <div class="parent-item parent-folder text-truncate">
                    <a href="javascript:void(0)" class="link-parent explore-tag"  data-tag="<?= $folder['id'] ?>">

                        <span class="tag-label text-truncate">
                            <?= $folder['nome'] ?>
                        </span>
                        <span class="mdi mdi-subdirectory-arrow-left icon mdi-24px"></span>
                    </a>
                </div>
            <?php } else { ?>
                <div class="parent-item parent-last text-truncate">
                    <div class="link-parent">
                        <span class="tag-label text-truncate">
                            <?= $folder['nome'] ?>
                        </span>
                        <span class="mdi mdi-mdi mdi-palette-swatch icon mdi-24px"></span>
                    </div>
                </div>
            <?php } ?>
        <?php
            $count++;
        } ?>
    </div>

    <div class="tags-list-manage">
        <div class="link-list-tag">
            <?php foreach ($children as $child) {
                    $prev = $associations[$child->id]['prev'] ?? null;
                    $next = $associations[$child->id]['next'] ?? null;
            ?>
            <?php   echo $this->render('parts/_tag', [
                    'model' => $child,
                    'prev' => $prev,
                    'next' => $next,
                    'occurrencies' => $occurrencies,
                    'parent_id' => $model->id,
                ]) ?>
            <?php } ?>
        </div>
        <div class="actions-tag-manage">
                <a href="#modalCreaNuovoTag" class="" data-toggle="modal" data-id="<?= $model->id ?>" id="linkCreaNuovoTag-general" class="d-flex">
                    <span class="mdi mdi-plus-box-outline mdi-18px m-r-5"></span>
                    <small><?= AmosTag::t('amostag', 'Crea nuovo tag'); ?></small>
                </a>
        </div>
    </div>
</div>


<?php
    Modal::begin([
        // 'header' => '<h5>' . AmosTag::t('amostag', 'Nascondi/mostra Tag') . '</h5>',
        'id' => 'modalHideShowTag',
    ]);
    ?>
<div id="modal-body"><?= AmosTag::t('amostag', 'Elaborazione dei dati in corso, attendere prego...'); ?></div>
    <?php Modal::end(); ?>
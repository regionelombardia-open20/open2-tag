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
 * @var $models [] \open20\amos\tag\models\Tag
 * @var $countSelected
 * @var $occurrencies
 */
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
?>
<div class="box-tag-grey">
    <div class="box-tags-selected">
        <div class="box-parent-tag">

            <div class="head-parent-tag">
                <div class="label-number-tag-selected">
                    <span class="text-truncate m-r-5"><?= AmosTag::t('amostag', "Tag selezionati") . ': ' ?></span>
                    <strong class="nSelectedTags"><?= $countSelected ?></strong>
                </div>
            </div>
            <div>
                <?php
                if ($countSelected > 0) {
                    $ids = [];
                    foreach ($models as $model) {
                        $ids[] = $model->id;
                        echo $this->render('parts/_selected_tags', [
                            'tagName' => $model->nome,
                            'root' => $model->root,
                            'id' => $model->id,
                            'path' => '/ ' . $model->getPath(false),
                            'disabled' => $model->disabled,
                            'readonly' => $model->readonly,
                            'occurrencyTags' => Tag::getOccurrencyTags([$model->root], null, $model->id),
                        ]);
                    }
                } else {
                    ?>
                    <p class="no-selected-tag"><?= AmosTag::t('amostag', "Nessun tag selezionato") ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="box-dati-tags">
            <div class="label-dato-tags">
                <span class="mdi mdi-bookmark-multiple mdi-24px m-r-5"></span>
                <span><?= AmosTag::t('amostag', "Contenuti in cui compaiono") ?></span>
                <a href="javascritp:void(0)" title="<?= AmosTag::t('amostag', "Numero di contenuti in cui compaiono i tag selezionati") ?>" data-toggle="toolitp" class="text-secondary m-r-5 m-l-5"><span class="mdi mdi-information-outline"></span></a>
                <strong><?= $occurrencies ?></strong>
            </div>
            <div class="label-dato-tags">
                <span class="mdi mdi-account-supervisor-circle mdi-24px m-r-5"></span>
                <span><?= AmosTag::t('amostag', "Potenziali utenti raggiunti") ?></span>
                <a href="javascritp:void(0)" title="<?= AmosTag::t('amostag', "Numero di potenziali utenti raggiunti con i tag selezionati") ?>" data-toggle="toolitp" class="text-secondary m-r-5 m-l-5"><span class="mdi mdi-information-outline"></span></a>
                <strong><?= $userInterestsCount ?></strong>
            </div>
        </div>
    </div>
    <div class="footer-box-tags-selected">
        <?php
        echo \yii\helpers\Html::a('<span class="mdi mdi-eye-off m-r-5"></span>' .
                AmosTag::t('amostag', AmosTag::t('amostag', 'Nascondi selezionati')),
                "#",
                [
                    'class' => $all_disabled == 1 ? 'btn btn-danger-inverse m-t-5 disabled' : 'btn btn-danger-inverse m-t-5',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalMultipleHideShowTag',
                    'data-id' => $ids,
                    'data-disabled' => 0,
                    'data-occurrencies' => $occurrencies,
                    'data-parent_id' => $parent_id,
                    'id' => 'linkMultipleHideShowTag-0',
                ]
        );
        ?>
        
        <?php
        echo \yii\helpers\Html::a('<span class="mdi mdi-eye m-r-5"></span>' .
                AmosTag::t('amostag', AmosTag::t('amostag', 'Mostra selezionati')),
                "#",
                [
                    'class' => $all_active == 1 ? 'btn btn-outline-primary m-t-5 disabled' : 'btn btn-outline-primary m-t-5',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalMultipleHideShowTag',
                    'data-id' => $ids,
                    'data-disabled' => 1,
                    'data-occurrencies' => $occurrencies,
                    'data-parent_id' => $parent_id,
                    'id' => 'linkMultipleHideShowTag-1',
                ]
        );
        ?>
    </div>
</div>
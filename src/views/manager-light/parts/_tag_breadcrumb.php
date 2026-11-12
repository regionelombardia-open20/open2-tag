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
 * @var $folderList
 * @var $folderCount
 */
?>
<div class="breadcrumbs breadcrumbs-tag">
    <?php $count = 1; ?>
    <?php foreach ($folderList as $folder) {
        if ($count < $folderCount) {?>
            <div class="tag-breadcrumb">
                <strong class="text-secondary separator">/</strong>
                <?php if($count == 1) { ?>
                    <span class="mdi mdi-folder-outline mdi-24px"></span>
                <?php } else { ?>
                    <span class="mdi mdi-palette-swatch-outline mdi-24px text-secondary"></span>
                <?php } ?>
                <a href="javascript:void(0)"  data-tag="<?= $folder['id'] ?>" class="text-tertiary tag-name explore-tag" title="<?= $folder['nome'] ?>">
                    <?= $folder['nome'] ?>
                </a>
            </div>
        <?php } else { ?>
            <div class="tag-breadcrumb">
                <strong class="text-secondary separator">/</strong>
                <?php if($count == 1) { ?>
                    <span class="mdi mdi-folder-outline mdi-24px"></span>
                <?php } else { ?>
                    <span class="mdi mdi-palette-swatch-outline mdi-24px text-secondary"></span>
                <?php } ?>
                <span class="text-secondary tag-name"><?= $folder['nome'] ?></span>
            </div>
        <?php } ?>
    <?php
        $count++;
    } ?>
</div>


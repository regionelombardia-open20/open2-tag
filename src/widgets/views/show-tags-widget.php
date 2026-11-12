<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\tag\widgets\views
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

use open20\amos\core\icons\AmosIcons;
use open20\amos\tag\AmosTag;

/**
 * @var \open20\amos\tag\models\Tag $root
 * @var \open20\amos\tag\models\Tag[] $tags
 * @var \yii\base\View $this
 */
?>

<h3 class="tags-title"><?= $root->nome ?></h3>
<?php if(!count($tags)) : ?>
    <?= AmosTag::t('amostag', '#no_tag_selected') ?>
<?php else: ?>
    <ul class="taglist">
        <?php foreach ($tags as $tag) : ?>
            <li class="tag-item">
                <div>
                    <?= AmosIcons::show('label') ?>
                    <span class="bold uppercase tag-label"><?= $tag->nome ?></span>
                    <?php
                    if ($tag->lvl > 1) {
                        $parent = $tag->parents(1)->one();
                        ?>
                        <p class="m-t-15">
                            <small class="italic"><?= $parent->nome ?></small>
                        </p>
                        <?php
                    }
                    ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<div class="clearfix"></div>


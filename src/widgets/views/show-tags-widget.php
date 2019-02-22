<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag\widgets\views
 * @category   CategoryName
 */

use lispa\amos\core\icons\AmosIcons;
use lispa\amos\tag\AmosTag;

/**
 * @var \lispa\amos\tag\models\Tag $root
 * @var \lispa\amos\tag\models\Tag[] $tags
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


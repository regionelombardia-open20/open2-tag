<?php

use open20\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var $tagContentsViewParams array
 * @var $tag \open20\amos\tag\models\Tag
 * @var $models_classname_id int
 */

?>

<div class="container">
    <div class="bi-plugin-header">
        <div class="flexbox title-heading-plugin">
            <div class="m-r-10">
                <div class="h3"><?= $tagContentsViewParams['titleSection'] ?></div>
            </div>
            <?= Html::a(Html::tag('span', $tagContentsViewParams['labelLinkAll']) . Html::tag('span', '', ['class' => 'icon mdi mdi-arrow-right-circle-outline']), ['detail', 'tag_id' => $tag->id, 'models_classname_id' => $models_classname_id], [
                'class' => 'link-all text-uppercase align-items-center small m-t-0',
                'title' => $tagContentsViewParams['titleLinkAll']
            ]) ?>
        </div>
    </div>
</div>
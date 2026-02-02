<?php
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


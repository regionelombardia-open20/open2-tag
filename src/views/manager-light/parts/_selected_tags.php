<?php


/**
 * @var $id
 * @var $tagName
 * @var $path
 */
 $totalOccurency = 0;
 foreach ($occurrencyTags as $ocTags) {
	$totalOccurency += $ocTags['occurrency'];
}
?>

<div id="selected_item_id_prev_<?=$id?>" class="item-selected-tags" data-key="<?= $id ?>">
    <div class="text-tag-selected text-truncate">
        <div class="item-tag-id" style="display:none"></div>
        <div class="item-tag-name text-truncate"><?= $tagName?></div>
        <div class="item-tag-path text-truncate" data-toggle="tooltip" data-placement="right" title="<?= $path ?>"><?= $path ?>
        
        </div>
        
        
    </div>
    <div class="actions-tag-selected">
        <?php if ($disabled) { ?>
            <span class="mdi mdi-eye-off mdi-18px m-l-5" style="float:right"></span>
        <?php } ?>
            <?php if ($readonly) { ?>
            <span class="mdi mdi-lock-outline mdi-18px m-l-5" style="float:right"></span>
        <?php } ?>
            <?php if ($totalOccurency > 0) { ?>
                <div class="tag-assegnati" >
                    <span class="mdi mdi-bookmark-outline"></span><?= $totalOccurency ?>
                </div>
            <?php } ?>
        </div>
</div>
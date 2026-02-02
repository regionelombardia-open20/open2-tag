<?php


/**
 * @var $id
 * @var $tagName
 * @var $path
 */
?>

<div id="selected_item_id_prev_<?=$id?>" class="item-selected-tags" data-key="<?= $id ?>">
    <div class="text-tag-selected text-truncate">
        <div class="item-tag-id" style="display:none"></div>
        <div class="item-tag-name text-truncate"><?= $tagName?></div>
        <div class="item-tag-path text-truncate" data-toggle="tooltip" data-placement="right" title="<?= $path ?>"><?= $path ?></div>
    </div>
    <div class="actions-tag-selected">
        
        <a class="item-tag-delete" href="#" title="Rimuovi tag"><span class="mdi mdi-minus-circle-outline"></span></a>
    </div>

</div>
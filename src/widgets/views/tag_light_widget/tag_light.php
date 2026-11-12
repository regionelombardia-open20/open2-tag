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

use \open20\amos\tag\AmosTag;
use yii\helpers\Html;

/**
 * @var $modelFormName
 * @var $name
 * @var $trees
 * @var $tags_selected_ids array
 * @var $tags_selected array
 * @var $is_search bool
 * @var $selectSonsOnly bool
 * @var $containerClass string
 * @var $id string
 * @var $collapseTree bool
 * @var $hideHeader bool
 * @var $occurrenciesTags array
 * @var $hideRootTrees bool
 */

$all_tags_selected_ids = [];
foreach ($tags_selected_ids as $root_id => $tagsTempIds) {
    foreach ($tagsTempIds as $tag_id_temp) {
        $all_tags_selected_ids[] = (string)$tag_id_temp;
    }
}

$this->registerJsVar('label_max_selezionati', AmosTag::t('amostag', 'Hai raggiunto il numero massimo di tag selezionabili'));
$this->registerJsVar('tags_selected_ids', $tags_selected_ids);
$this->registerJsVar('all_tags_selected_ids', $all_tags_selected_ids);
$js = <<<JS
    var selectedTags = all_tags_selected_ids;
    var selectedTagsForRoot = tags_selected_ids;

    $(document).ready(function () {
    // Itera su ogni key dell'oggetto tags_selected_ids
        Object.keys(tags_selected_ids).forEach(root_id => {
            let selectedTags = tags_selected_ids[root_id]; // Ottieni gli ID per la key
            let selectedTagsString = selectedTags.join(','); // Converti in stringa separata da virgola
            
            // Assegna il valore all'input con id corrispondente
            $('#tree_obj_' + root_id).val(selectedTagsString);
        });
    });
    
     function updateCheckedTags(){
          $('.tag-is-checked').each(function(e){
              $(this).hide().html('<span class="mdi mdi-check-circle-outline text-primary"></span>');
          });
          
            $('.item_tag_id:checked').each(function(e){
            let currentUl = $(this).closest('.li-link-list');
            let uls = $(currentUl).parentsUntil('.tag-light-widget','.li-link-list');
    
            // Conta il numero di tag checked per ciascun livello
                $(uls).each(function(){
                    let nChecked = $(this).find('.item_tag_id:checked').length;  // Conteggio dei tag checked nel livello corrente
                    $(this).find('.item-tree .tag-is-checked').first()
                        .show()
                        .html('<span class="mdi mdi-check-circle-outline text-primary"></span>' + nChecked);
                });
            });
        }
    
    // SELECT TAG CHECKBOX 
    $(document).on('click', '.item_tag_id', function (e){
        let elementToCopy = $('#element-to-copy .item-selected-tags').clone();
        let root_id = $(this).attr('data-root');
        let tag_id = $(this).attr('data-key');
        let tree = $('#tag_tree_light_root_'+root_id);
        let countSelected = (selectedTagsForRoot[root_id]!== undefined ) ? selectedTagsForRoot[root_id].length : 0;
        let limit_tree = $(tree).attr('data-limit');
                console.log(tags_selected_ids[root_id]);

     
        if(selectedTagsForRoot[root_id] === undefined){
                selectedTagsForRoot[root_id] = [];
        }
        
        if($(this).is(':checked')){
            if(countSelected >= parseInt(limit_tree)){
                alert(label_max_selezionati);
                return false;
            }
            
            selectedTags.push(tag_id);
            selectedTagsForRoot[root_id].push(tag_id);
            //add tag to preview
            let tagItem = $('#tree_light_tag_item_'+tag_id);
            $(elementToCopy).find('.item-tag-name').text(tagItem.find('.name-tag').text());
            $(elementToCopy).find('.item-tag-path').text(tagItem.find('.path-tag').text());
            $(elementToCopy).attr('data-key',tag_id);
            $(elementToCopy).attr('id','selected_item_id_prev_'+tag_id);
            $('#selectedTagContainer'+root_id).append(elementToCopy);
            $(tagItem).addClass('item-tree-checked');
            updateCheckedTags();
        }else{
            selectedTags = selectedTags.filter(function(value) {
                let ok =  parseInt(value) !== parseInt(tag_id);
                if(!ok){
                    $('#selected_item_id_prev_'+tag_id).remove();
                }
                return ok;
            });
             selectedTagsForRoot[root_id] = selectedTagsForRoot[root_id].filter(function(value) {
                let ok =  parseInt(value) !== parseInt(tag_id);
                if(!ok){
                    $('#selected_item_id_prev_'+tag_id).remove();
                }
                return ok;
            });
            let tagItem = $('#tree_light_tag_item_'+tag_id);
            $(tagItem).removeClass('item-tree-checked');
            updateCheckedTags();
        }
        
        //console.log(selectedTags);
        let selectedTagsString = selectedTags.join(',');
        $('#tree_obj_'+root_id).val(selectedTagsString);
        $('.nSelectedTags'+root_id).text(selectedTagsForRoot[root_id].length);
    });
    
    // DELETE TAG FROM PREVIEW
    $(document).on('click', '.item-tag-delete', function (e){
        e.preventDefault();
        let parent = $(this).parents('.item-selected-tags');
        let tag_id = $(parent).attr('data-key');
        let root_id = $(this).parents('.selectedTags').attr('data-root');
        selectedTags = selectedTags.filter(function(value) {
            let ok =  parseInt(value) !== parseInt(tag_id);
            if(!ok){
                $('#selected_item_id_prev_'+tag_id).remove();
            }
            return ok;
        });
         if(selectedTagsForRoot[root_id] === undefined){
                selectedTagsForRoot[root_id] = [];
        }
        
        selectedTagsForRoot[root_id] = selectedTagsForRoot[root_id].filter(function(value) {
                let ok =  parseInt(value) !== parseInt(tag_id);
                if(!ok){
                    $('input[name="tag_value_'+tag_id+'"]')
                    .prop('checked', false);
                    $('#selected_item_id_prev_'+tag_id).remove();
                }
                return ok;
        });
        let selectedTagsString = selectedTags.join(',');
        $('#tree_obj_'+root_id).val(selectedTagsString);
        $('.nSelectedTags'+root_id).text(selectedTagsForRoot[root_id].length);
        let tagItem = $('#tree_light_tag_item_'+tag_id);
        $(tagItem).removeClass('item-tree-checked');
        updateCheckedTags();
        //console.log('selectedTags', selectedTags);
        //console.log('selectedTagsForRoot', selectedTagsForRoot);
    });

    // BUTTONS HIDE/SHOW  TREE
    $(document).on('click', '.showHideTree', function (e){
        e.preventDefault();
        let root_id = $(this).attr('data-key');
           $('.showHideTree').each(function(e){
            if($(this).attr('data-key') == root_id){
                $(this).addClass('active');
                $(this).addClass('btn-tertiary');
                $(this).removeClass('btn-tertiary-outline');
                $(this).find('.icon').show();
            } else{
                $(this).removeClass('active');
                $(this).removeClass('btn-tertiary');
                $(this).addClass('btn-tertiary-outline');
                $(this).find('.icon').hide();
            }
        })
        $('.tag-tree-light').each(function(e){
            if($(this).attr('data-root') == root_id){
                $(this).show();
            } else{
               $(this).hide();
            }
        })
    });
    
    // CERCA TAG
    $(document).on('keyup', '#search_tags_input', function() {
       // console.log( 'striga da cercare',$(this).val());

       var searchString = $(this).val().toLowerCase(); // La stringa da cercare
        $('.name-tag strong').each(function() {
            $(this).replaceWith($(this).text());
        }); 
        if(searchString !== ''){ // Se la stringa è vuota
            $('.tag-light-widget li').hide();
            $('.tag-light-widget ul').addClass('in').attr('aria-expanded', 'true');
            $('.tag-light-widget .icon-collapse a').removeClass('collapsed').attr('aria-expanded', 'true');
       }else{
            $('.tag-light-widget li').show();
            $('.name-tag').attr('style','');
            return;
       }
        
        $('.tag-light-widget .title-item-tree .name-tag').each(function() { // Cerca solo all'interno del div con la classe tag-light-widget
            var elementText = $(this).text().toLowerCase(); // Ottieni il testo dell'elemento in minuscolo
            console.log('elementText',elementText);

            if ( elementText.includes(searchString)) { // Se contiene la stringa
                // Evidenzia lo sfondo in giallo
               // $(this).attr('style','background-color: #d4e4d7');
    
                // Sostituisci la stringa cercata con una versione in grassetto
                var originalText = $(this).html(); // Ottieni il testo HTML originale
                var regex = new RegExp("(" + searchString + ")", "gi"); // Crea un'espressione regolare per trovare la stringa
                var highlightedText = originalText.replace(regex, '<strong class="evidenziato" style="background-color: yellow">$1</strong>'); // Sostituisci con <strong>
                $(this).html(highlightedText); // Imposta il nuovo HTML
                
                   // Mostra il <ul> che contiene questo <li>
                $(this).closest('li').show();

            // Mostra tutti i <ul> padri fino alla radice
               $(this).parents('li').show();
            }else{
                $(this).attr('style','');
            }
        });
    });
    
    updateCheckedTags();
JS;
$this->registerJs($js);

$activeTree = null;
$isOnlyOneTag = count($trees) == 1;
?>
<div id="<?= $id ?>" class="tag-light-widget <?= $containerClass ?>">
    <?php if (!$hideHeader) { ?>
        <div class="intestazione-box">
            <h3 class="tags-title">
                <?= AmosTag::t('amostag', 'Tag Aree di Interesse') ?>
            </h3>
        </div>
    <?php } ?>


    <div class="tag-select">
        <?php if (!$isOnlyOneTag && !$hideRootTrees) { ?>
            <div class="text-uppercase text-secondary m-b-10">
                <strong><?= AmosTag::t('amostag', "Seleziona un albero di tag") ?></strong>
            </div>
        <?php } ?>

        <?php foreach ($trees as $rootTree) {
            $rootModel = $rootTree['rootModel'];
            if (is_null($activeTree)) {
                $activeTree = $rootModel->id;
            }

            $countSelected = 0;
            if (!empty($tags_selected_ids[$rootModel->id])) {
                $countSelected = count($tags_selected_ids[$rootModel->id]);
            }
            $limitstring = '';
            if (!empty($rootModel->limit_selected_tag)) {
                $limitstring = '<span class="separator">|</span>' . "<strong class='nSelectedTags" . $rootModel->id . "'>" . $countSelected . "</strong>/<span>{$rootModel->limit_selected_tag}</span>";
            }
            ?>
            <?php if (!$hideRootTrees) { ?>
                <?= \yii\helpers\Html::a(
                    ($activeTree == $rootModel->id ? '<span class="mdi mdi-subdirectory-arrow-right icon"></span> ' : '<span style="display:none" class="mdi mdi-subdirectory-arrow-right icon"></span> ') . $rootModel->nome . $limitstring, // L'icona viene aggiunta prima del testo
                    '#',
                    [
                        'class' => 'showHideTree btn-tree btn lead ' . ($activeTree == $rootModel->id ? 'btn-tertiary active' : 'btn-tertiary-outline'),
                        'data-key' => $rootModel->id
                    ]
                ) ?>
            <?php } ?>

        <?php } ?>
    </div>

    <?php if ($is_search) { ?>
        <div class="row">
            <div class="col-md-7 m-t-15">

                <div class="form-group">
                    <strong class="text-tertiary small"><?= AmosTag::t('amostag', 'Cerca tag') ?></strong>
                    <?= Html::textInput('search_tags', '', [
                        'placeholder' => AmosTag::t('amostag', 'Inizia a digitare per cercare tra i tag'),
                        'id' => 'search_tags_input',
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="text-uppercase text-secondary m-b-10 m-t-15">
        <strong><?= AmosTag::t('amostag', "Naviga e scegli i tag da assegnare") ?></strong>
    </div>
    <div class="content-widget-tag-light row">
        <div class="col-md-7">
            <?php foreach ($trees as $root_id => $tree) {
                $rootModel = $tree['rootModel'];
                $activeStyle = "display:none";
                if ($rootModel->id == $activeTree) {
                    $activeStyle = "";
                }
                ?>
                <div style="<?= $activeStyle ?>" id="tag_tree_light_root_<?= $root_id ?>" class="tag-tree-light"
                     data-root="<?= $root_id ?>" data-limit="<?= $rootModel->limit_selected_tag ?>">
                        <?= Html::hiddenInput("{$modelFormName}[$name][$root_id]", null, ['id' => 'tree_obj_' . $root_id]) ?>
                    <?php
                    //                    pr($tree['tags']);die;
                    echo \open20\amos\core\forms\editors\TreeWidget::widget([
                        'treeStructure' => $tree['tags'],
                        'itemView' => '@vendor/open20/amos-tag/src/widgets/views/tag_light_widget/_tag_item',
                        'assetPath' => 'open20\amos\tag\assets\TreeTagLightAsset',
                        'treeCollapsed' => $collapseTree,
                        'viewParams' => [
                            'tags_selected_ids' => $tags_selected_ids,
                            'selectSonsOnly' => $selectSonsOnly,
                            'treeCollapsed' => $collapseTree,
                            'occurrenciesTags' => $occurrenciesTags,
                            'labelContent' => $labelContent,
                        ]
                    ]) ?>
                </div>
            <?php } ?>
        </div>
        <!--    PREVIEW SELECTED TAGS-->
        <div class="col-md-5">
            <div class="box-tags-selected">
                <?php foreach ($trees as $rootTree) { ?>
                    <div class="box-parent-tag">
                        <?php $rootModel = $rootTree['rootModel'];
                        $countSelected = 0;
                        if (!empty($tags_selected_ids[$rootModel->id])) {
                            $countSelected = count($tags_selected_ids[$rootModel->id]);
                        }
                        $limitstring = '';
                        if (!empty($rootModel->limit_selected_tag)) {
                            $limitstring = "/" . $rootModel->limit_selected_tag;
                        }
                        ?>
                        <div class="head-parent-tag">
                            <div class="title-parent-tag text-secondary text-truncate">
                                <strong><?= $rootModel->nome ?></strong>
                            </div>
                            <div class="label-number-tag-selected">
                                <span class="text-truncate m-r-5"><?= AmosTag::t('amostag', "Tag selezionati") . ': ' ?></span>
                                <strong class="nSelectedTags<?= $rootModel->id ?>"><?= $countSelected ?></strong><?= $limitstring ?>
                            </div>
                        </div>
                        <div data-root="<?= $rootModel->id ?>" id="selectedTagContainer<?= $rootModel->id ?>"
                             class="selectedTags">
                            <?php
                            if (!empty($tags_selected[$rootModel->id])) {
                                foreach ($tags_selected[$rootModel->id] as $tag) {
                                    $tagModel = \open20\amos\tag\models\Tag::findOne($tag['id']); ?>
                                    <?= $this->render('@vendor/open20/amos-tag/src/widgets/views/tag_light_widget/selected_tags', [
                                        'tagName' => $tag['nome'],
                                        'root' => $tag['root'],
                                        'id' => $tag['id'],
                                        'path' => '/ ' . $tagModel->getPath()
                                    ]) ?>
                                <?php }
                            } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div id="element-to-copy" style="display:none">
    <?= $this->render('@vendor/open20/amos-tag/src/widgets/views/tag_light_widget/selected_tags') ?>
</div>
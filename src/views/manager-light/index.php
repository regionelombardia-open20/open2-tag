<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\tag
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
 * @var $dataProvider
 */
use open20\amos\tag\AmosTag;
use \yii\bootstrap\Modal;
use open20\amos\tag\assets\TreeTagLightAsset;
use open20\amos\tag\models\Tag;
use kartik\tree\TreeView;
use open20\amos\layout\assets\ThreeDotsAsset;
use open20\amos\core\forms\editors\Select;
use yii\helpers\Url;
use yii\web\JsExpression;

TreeTagLightAsset::register($this);
ThreeDotsAsset::register($this);

$this->title = AmosTag::t('amostag', 'Gestione Tag');
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = \Yii::$app->user->can('AMMINISTRATORE_TAG');
$module = TreeView::module();
$security = Yii::$app->security;
$modelClass = Tag::className();
$allowNewRoots = true;
$dataToHash = $modelClass . $allowNewRoots;
$treeMoveHash = $security->hashData($dataToHash, $module->treeEncryptSalt);
$explode = explode('open20', $treeMoveHash);
$treeMoveHash = $explode[0] . 'open20\\\amos\\\tag\\\models\\\Tag1';

$this->registerJsVar('parent_id', $model->id);
$js = <<<JS
    //AGGIORNA ALBERO TAG DI SINISTRA
    detailTree = function(id, tag_id = null){
        let box = '<div id="empty-box" class="box-tags-selected"><p>Seleziona uno o più tag per gestirli</p></div>';
        $.ajax({
                url : '/tag/manager-light/detail-tree',
                type: 'GET',
                data: {
                        'id': id,
                },
                dataType: 'json',
                success: function (data) {
                    $('#left-section').html(data.htmlTree);
                    $('#tag-breadcrumb-section').html(data.htmlBreadcrumb);
                    $('.loader-3dots').hide();
                    $('#right-section').html(box);
                    if(tag_id){
                           var selectedValues = [];
                           var checkbox =  $('.btn-checkbox input[value="' + tag_id.toString() + '"]');
                           checkbox.prop('checked', true);
                           selectedValues.push(tag_id);
                           $.ajax({
                                   url: '/tag/manager-light/info-tags',
                                   type: 'POST',
                                   data: {
                                           ids: selectedValues, 
                                   },
                           success: function(response) {
                                    $('#right-section').html(response);
                           },
                    });
                   }
                },
        });
    }
    
    // CAMBIA ALBERO, SELEZIONA UN ALBERO DA ESPLORARE
    $(document).on('click', '.chooseTagTree', function (e) {
            e.preventDefault();
            $('.loader-3dots').show();
            let tag_id = $(this).attr('data-tag'); 
            detailTree(tag_id);
            $('.chooseTagTree').each(function(e){
                let current_tag_id = $(this).attr('data-tag');
                if(current_tag_id == tag_id){
                    $(this)
                        .addClass('active')
                        .addClass('btn-tertiary')
                        .removeClass('btn-tertiary-outline');
                    
                    $('#dropdownOtherPluginMenu_'+current_tag_id)
                        .addClass('active')
                        .removeClass('btn-tertiary-outline')
                        .addClass('btn-tertiary');
                        
                }else{
                    $(this)
                        .removeClass('active')
                        .removeClass('btn-tertiary')
                        .addClass('btn-tertiary-outline');
                    
                    $('#dropdownOtherPluginMenu_'+current_tag_id)
                         .removeClass('active')
                        .removeClass('btn-tertiary')
                        .addClass('btn-tertiary-outline');
                }
            });
            let box = '<div id="empty-box" class="box-tags-selected"><p>Seleziona uno o più tag per gestirli</p></div>'
            $('#right-section').html(box);
    });

    // CREA NUOVO TAG
    $(document).on('click', 'a[id^="linkCreaNuovoTag-"]', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
    
            $.ajax({
                url : '/tag/manager-light/create-tree',
                type: 'GET', 
                data: { id: id }, 
                success: function(data) {
                    $('#modalCreaNuovoTag .modal-body').html(data);
                    $('#modalCreaNuovoTag').modal('show');
                },
            });
    });
        
    // AGGIORNA TAG
    $(document).on('click', '[id^="linkUpdateTag-"]', function (e) {
        e.preventDefault(); 
        var id = $(this).data('id'); 
        $.ajax({
            url : '/tag/manager-light/update-tree',
            type: 'GET', 
            data: { id: id }, 
            success: function(data) {
                $('#modalUpdateTag .modal-body').html(data);
                $('#modalUpdateTag').modal('show');
            },
        });
    });
        
    // NASCONDI MOSTRA
    $(document).on('click', '[id^="linkHideShowTag-"]', function (e) {
        e.preventDefault(); 
        var id = $(this).data('id'); 
        var disabled = $(this).data('disabled'); 
        $('#modalHideShowTag').modal('show');
        $('#modalHideShowTag .modal-body').html('Elaborazione dei dati in corso, attendere prego...');
        $.ajax({
            url : '/tag/manager-light/hide-show',
            type: 'GET', 
            data: { id: id }, 
            success: function(data) {
                if(disabled){
                    $('#modalHideShowTag .modal-header').html('<div class="modal-title h6"><span class="mdi mdi-eye modal-icon-title text-tertiary m-r-10"></span>Vuoi mostrare questo tag?<button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }else{
                   $('#modalHideShowTag .modal-header').html('<div class="modal-title h6"><span class="mdi mdi-eye-off modal-icon-title text-danger m-r-10"></span>Vuoi nascondere questo tag?<button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'); 
                }
                $('#modalHideShowTag .modal-body').html(data);
                $('#modalHideShowTag').modal('show');
            },
        });
    });
	
	//NASCONDI MOSTRA MULTIPLO
    $(document).on('click', '[id^="linkMultipleHideShowTag-"]', function (e) {
		e.preventDefault(); 
		var id = $(this).data('id'); 
                var disabled = $(this).data('disabled'); 
                var occurrencies = $(this).data('occurrencies'); 
                var parent_id = $(this).data('parent_id'); 
		$.ajax({
            url : '/tag/manager-light/multiple-hide-show',
            type: 'GET', 
            data: { 'id[]': id, 
                    disabled: disabled,
                    occurrencies: occurrencies,
                    parent_id: parent_id,
                    }, 
            success: function(data) {
                if(disabled){
                    $('#modalMultipleHideShowTag .modal-header').html('<div class="modal-title h6"><span class="mdi mdi-eye modal-icon-title text-tertiary m-r-10"></span>Vuoi mostrare questo tag?<button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }else{
                   $('#modalMultipleHideShowTag .modal-header').html('<div class="modal-title h6"><span class="mdi mdi-eye-off modal-icon-title text-danger m-r-10"></span>Vuoi nascondere questo tag?<button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'); 
                }
                $('#modalMultipleHideShowTag .modal-body').html(data);
                $('#modalMultipleHideShowTag').modal('show');
            },
        });
	});
    
    //ORDINA TAG IN SU
    $(document).on('click', '.prev-btn', function(e) {
        e.preventDefault();
        $('.loader-3dots').show();

        let currentId = $(this).data('current-id');
        let prevId = $(this).data('prev-id');
        let parentId = $(this).data('parent-id');

        $.ajax({
            url: '/treemanager/node/move',
            type: 'POST',
            data: {
                idFrom: currentId,
                idTo: prevId,
                modelClass: 'open20\\\\amos\\\\tag\\\\models\\\\Tag',
                dir: 'u',
                allowNewRoots : true,
                treeMoveHash: '$treeMoveHash'
            },
            success: function(response) {
               detailTree(parentId); 
            },
        });
    });

    //ORDINA TAG IN GIU
    $(document).on('click', '.next-btn', function(e) {
        e.preventDefault();
        $('.loader-3dots').show();
        let currentId = $(this).data('current-id');
        let nextId = $(this).data('next-id');
        let parentId = $(this).data('parent-id');
        
        $.ajax({
            url: '/treemanager/node/move', 
            type: 'POST',
            data: {
                idFrom: currentId,
                idTo: nextId,
                modelClass: 'open20\\\\amos\\\\tag\\\\models\\\\Tag',
                dir: 'd',
                allowNewRoots : true,
                treeMoveHash: '$treeMoveHash'
            },
            success: function(response) {
                detailTree(parentId); 
            },
        });
    });
    
    //SELEZIONA TAG, MOSTRA I SELEZIONATI NELLA SEZIONE DI DESTRA
    $(document).on('change', '.model-checkbox', function() {
        if($(this).is(':checked')) {
            $(this).closest('.item-tree').addClass('item-tree-checked');
        } else {
            $(this).closest('.item-tree').removeClass('item-tree-checked');
        }
        
        let selectedValues = $('.model-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedValues.length > 0) {
            $.ajax({
                url: '/tag/manager-light/info-tags',
                type: 'POST',
                data: {
                    ids: selectedValues, 
                },
                success: function(response) {
                     $('#right-section').html(response);
                },
            });
        }else{
            let box = '<div id="empty-box" class="box-tags-selected"><p>Seleziona uno o più tag per gestirli</p></div>'
            $('#right-section').html(box);
            }
    });
        
    $('#tag-search').on('select2:select', function(e) {
		
        var data = e.params.data;
        var parentId = data.parent_id;
        var tag_id = data.id;
        if (parentId) {
            detailTree(parentId, tag_id);
        }
    });
    
    
    $(document).on('click',".explore-tag", function(e){
        e.preventDefault();
        let tag_id = $(this).attr('data-tag');
        $('.loader-3dots').show();
        detailTree(tag_id);
    })
  
JS;
$this->registerJs($js);
?>
<div class="row m-b-30">
    <div class="generator col-lg-6">
        <div class="text-secondary">
            <strong><?= AmosTag::t('amostag', 'Cerca tag'); ?></strong>
        </div>
        <?=
        Select::widget([
            'data' => [],
            'name' => 'tag_id',
            'options' => [
                'multiple' => false,
                'placeholder' => AmosTag::t('amostag', 'Ricerca per nome...'),
                'id' => 'tag-search',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'closeOnSelect' => true,
                'ajax' => [
                    'url' => Url::to(['/tag/manager-light/search-tag']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
            ],
        ])
        ?>
    </div>
</div>

<div class="tag-form">
    <div class="text-uppercase text-secondary m-b-15">
        <strong><?= AmosTag::t('amostag', "Seleziona un albero di tag") ?></strong>
    </div>



    <div class="tags-list-parent m-t-10">
        <?php foreach ($dataProvider->models as $tag) { ?>
            <div class="btn-tag-parent btn-group">

                <a data-tag="<?= $tag->id ?>" class="chooseTagTree btn-tree btn lead btn-tertiary-outline">
                    <span class="mdi mdi-subdirectory-arrow-right icon-sub"></span>
                    <span class="tag-label">
                        <?= $tag->nome ?>
                    </span>
                </a>

                <div class="btn-group">
                    <button class="btn-tree btn lead btn-tertiary-outline btn-other-menu dropdown-toggle" type="button" id="dropdownOtherPluginMenu_<?= $tag->id ?>" data-tag="<?= $tag->id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="<?= AmosTag::t('amostag', 'Gestisci') ?>">
                        <span class="mdi mdi-dots-vertical mdi-24px"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <?=
                            \yii\helpers\Html::a(
                                    AmosTag::t('amostag', 'Modifica'),
                                    "#",
                                    [
                                        'class' => ' float-right mr-2',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalUpdateTag',
                                        'data-id' => $tag->id,
                                        'id' => 'linkUpdateTag-' . $tag->id,
                                    ]
                            );
                            ?>
                        </li>
                        <li>
                            <?php
                            echo \yii\helpers\Html::a(
                                    AmosTag::t('amostag', $model->disabled ? AmosTag::t('amostag', 'Mostra') : AmosTag::t('amostag', 'Nascondi')),
                                    "#",
                                    [
                                        'class' => $model->disabled ? '' : 'text-danger',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalHideShowTag',
                                        'data-id' => $model->id,
                                        'data-visible' => $model->disabled,
                                        'id' => 'linkHideShowTag-' . $model->id,
                                    ]
                            );
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>


    <div class="tag-views">
        <div id="tag-breadcrumb-section" class="content-breadcrumb"></div>
        <div class="content-widget-tag-light row">
            <div class="left-box col-md-7 m-b-20" id="left-section">
                <div class="navigation-manage-tag">
                    <div style="display: none" class="loader-3dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="right-box col-md-5 m-b-20" id="right-section">
            </div>
        </div>
    </div>


    <?php
    Modal::begin([
        'header' => '<h5>' . AmosTag::t('amostag', 'Crea un nuovo Tag') . '</h5>',
        'size' => 'modal-lg',
        'id' => 'modalCreaNuovoTag',
        'options' => [
            'class' => 'modal-right' // Classe personalizzata per il modal principale
        ],
    ]);
    ?>
    <?php Modal::end(); ?>


    <?php
    Modal::begin([
        'header' => '<h5>' . AmosTag::t('amostag', 'Modifica Tag') . '</h5>',
        'size' => 'modal-lg',
        'id' => 'modalUpdateTag',
        'options' => [
            'class' => 'modal-right' // Classe personalizzata per il modal principale
        ],
    ]);
    ?>
    <?php Modal::end(); ?>


    <?php
    Modal::begin([
        // 'header' => '<h5>' . AmosTag::t('amostag', 'Nascondi/mostra Tag') . '</h5>',
        'id' => 'modalMultipleHideShowTag',
    ]);
    ?>

    <?php Modal::end(); ?>
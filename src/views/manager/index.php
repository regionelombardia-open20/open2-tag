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

use open20\amos\tag\models\Tag;
use open20\amos\tag\utility\TagUtility;
use kartik\tree\TreeView;
use kartik\tree\Module;
use open20\amos\tag\AmosTag;
use yii\helpers\Url;

$this->title = AmosTag::t('amostag', 'Gestione Tag');
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = \Yii::$app->user->can('AMMINISTRATORE_TAG');


$js = <<<JS

    // If root tag is only admin, disable create button
    var isAdmin = '{$isAdmin}';
    if (!isAdmin) {
        var createBtn = $('.kv-footer-container button.kv-create');
        var saveBtn = $('.kv-detail-heading button[type="submit"]');
        
        // Check if selected tag is only admin on page load
        createBtn.prop('disabled', true);
        if (saveBtn.length > 0) {
            createBtn.prop('disabled', false);
        }
    
        // On root click
       $('li.kv-parent').on('click', function() {
            var id = $(this).data('key');
            isRootOnlyAdmin(id);
       });
        
       // On child click 
       $('li.kv-parent li').on('click', function(event) {
           event.stopPropagation();
           var id = $(this).data('key');
           isRootOnlyAdmin(id);
       });
        
       // Check if root tag is only admin
       function isRootOnlyAdmin(id) {
           createBtn.prop('disabled', true);
           $.ajax({
               url: '/tag/manager/is-root-only-admin',
               type: 'POST',
               dataType: 'json',
               data: {
                   tagId: id
               },
               success: function(data) {
                   if (data) {
                       createBtn.prop('disabled', true);
                   } else {
                       createBtn.prop('disabled', false);
                   }
               }
           });
       }
   }

JS;

$this->registerJs($js, \yii\web\View::POS_LOAD);
?>

<style>
    /**
        @todo Add all AM ICONS to iconEditSettings listData property
    */
    /*.field-tag-icon_type {*/
    /*    display: none;*/
    /*}*/
</style>
<div class="tag-form">

    <?= TreeView::widget([
        'headerTemplate' => '<div class="row">
                                    <div class="col-sm-12">
                                        {heading}
                                        {search}
                                    </div>
                                </div>',
        'query' => Tag::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => AmosTag::tHtml('amostag', 'Liste tag')],
        'rootOptions' => ['label' => '<span class="text-primary">' . AmosTag::t('amostag', 'Radice tag') . '</span>'],
        'fontAwesome' => false,
        'nodeView' => '@vendor/open20/amos-tag/src/views/manager/_form', // Overwritten default form view, comment this to use original view
        'showIDAttribute' => $isAdmin,
        'isAdmin' => $isAdmin,
        'displayValue' => 1,
        'iconEditSettings' => [
            'show' => 'text',
            'type' => 1,
        ],
        'softDelete' => false,
        'cacheSettings' => ['enableCache' => true],
        'nodeAddlViews' => [
//            Module::VIEW_PART_1 => '',
            Module::VIEW_PART_2 => '@vendor/open20/amos-tag/src/views/manager/custom_fields',
            Module::VIEW_PART_3 => '@vendor/open20/amos-tag/src/views/manager/advanced_admin_options', // FOR ADMIN ONLY
            Module::VIEW_PART_4 => '@vendor/open20/amos-tag/src/views/manager/roots_advanced', // FOR ADMIN ONLY
//            Module::VIEW_PART_5 => '@vendor/open20/amos-tag/src/views/manager/roots_advanced',
        ],
        'toolbar' => TagUtility::toolbarTreeViewWidget($isAdmin),
    ]);
    ?>
</div>
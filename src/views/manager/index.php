<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
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
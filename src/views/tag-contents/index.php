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


use open20\amos\core\helpers\Html;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\tag\models\Tag;

/**
 * @var $this \yii\web\View
 * @var $tag Tag
 * @var $modelParams array
 * @var $isActionIndex bool
 */

\open20\amos\tag\assets\TagContentsAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/tag']];
$this->params['breadcrumbs'][] = ['label' => 'Tag', 'url' => ['index']];

if ($isActionIndex) {
    $titleSection = \Yii::t('amostag', 'Tutti i contenuti con tag: {tagName}', [
        'tagName' => '<span>' . $tag->nome . '</span>'
    ]);
    $this->title = strip_tags($titleSection);
    $this->params['forceBreadcrumbs'][] = ['label' => $tag->nome];
} else {
    $models_classname_id = Yii::$app->request->get('models_classname_id');
    $models_classname = ModelsClassname::findOne($models_classname_id);
    $modelClassname = $models_classname->classname;
    if (empty($modelParams[$modelClassname]['viewParams']['titleSection'])) {
        $modelParams[$modelClassname]['viewParams']['titleSection'] = \Yii::t('amostag', '{modelLabel} con tag: {tagName}', [
            'modelLabel' => $models_classname->label,
            'tagName' => '<span>' . $tag->nome . '</span>'
        ]);
    }
    $titleSection = $modelParams[$modelClassname]['viewParams']['titleSection'];
    $this->title = strip_tags($titleSection);
    $this->params['forceBreadcrumbs'][] = ['label' => $tag->nome, 'url' => ['index', 'tag_id' => $tag->id]];
    $this->params['forceBreadcrumbs'][] = ['label' => $this->title];
}

$userId = \Yii::$app->user->id;
$userTagInterestIds = \open20\amos\cwh\utility\CwhUtil::findInterestTagIdsByUser($userId);
$tagId = $tag->id;
$tagName = $tag->nome;
$isTagActive = in_array($tagId, $userTagInterestIds);
$addTagText = Yii::t('amostag', 'Segui il tag');
$addTagTitle = BaseAmosModule::t('amoscore', 'Aggiungi il tag al tuo profilo');
$btnIconClassAdd = 'tag-action-btn-icon mdi mdi-plus-circle-outline mdi-18px m-r-5';
$removeTagText = Yii::t('amostag', 'Rimuovi il tag');
$removeTagTitle = BaseAmosModule::t('amoscore', 'Rimuovi il tag dal tuo profilo');
$btnIconClassRemove = 'tag-action-btn-icon mdi mdi-minus-circle-outline mdi-18px m-r-5';
$removeTagModalBody = BaseAmosModule::t('amoscore', 'Rimuovendo il tag <strong>"{tag_name}"</strong> dai tuoi interessi, non ti verranno più suggeriti i contenuti nelle sezioni apposite e nei riepiloghi via mail.<br><br>Vuoi procedere? Ti ricordiamo che potrai aggiungere nuovamente il tag in un secondo momento.');


// Hide layout page header
$jsHeader = <<<JS
    $('div .page-header').hide();
JS;
$this->registerJs($jsHeader);

$jsAddRemoveTag = <<<JS

var addTagText = '{$addTagText}';
var addTagTitle = '{$addTagTitle}';
var btnIconClassAdd = '{$btnIconClassAdd}';
var removeTagText = '{$removeTagText}';
var removeTagTitle = '{$removeTagTitle}';
var btnIconClassRemove = '{$btnIconClassRemove}';
var tagId = '{$tagId}';
var tagName = '{$tagName}';
var removeTagModalBody = '{$removeTagModalBody}';
var userId = '{$userId}';

$('body').on('click', '.custom-page-header .btn.tag-action', function(e) {
    let btn = $(this);
    var isActive = btn.hasClass('tag-active');
    if (isActive) {
        // replace tag_name with the tag name
        $('#confirm-remove-tag-modal .modal-body p').html(removeTagModalBody.replace('{tag_name}', tagName));
        $('#confirm-remove-tag-modal').modal('show');
    } else {
        toggleTag(tagId, userId, btn, isActive);
    }
    $('#confirm-remove-tag-modal .confirm-remove-btn').on('click', function() {
        $('#confirm-remove-tag-modal').modal('hide');
        toggleTag(tagId, userId, btn, true);
    });
});

function toggleTag(tagId, userId, btn, isActive) {
    $.ajax({
        url: '/amosadmin/user-profile-ajax/' + (isActive ? 'remove-tag' : 'add-tag'),
        type: 'GET',
        data: {
            'id': tagId,
            'user_id': userId
        },
        dataType: 'json',
        beforeSend: function () {
            // btn.tooltip('hide');
            btn.prop('disabled', true);
        },
        success: function (data) {
            let btnIcon = btn.find('.mdi');
            if (isActive) {
                btn.removeClass('btn-danger-inverse tag-active').addClass('btn-primary tag-inactive');
                btnIcon.removeClass(btnIconClassRemove).addClass(btnIconClassAdd);
                btn.find('.btn-text').text(addTagText);
                btn.attr('data-original-title', addTagTitle);
            } else {
                btn.addClass('btn-danger-inverse tag-active').removeClass('btn-primary tag-inactive');
                btnIcon.removeClass(btnIconClassAdd).addClass(btnIconClassRemove);
                btn.find('.btn-text').text(removeTagText);
                btn.attr('data-original-title', removeTagTitle);
            }
        },
        error: function (request, error) {
            console.log(error);
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
}

JS;
$this->registerJs($jsAddRemoveTag);

?>

<div class="container">
    <div class="custom-page-header d-flex justify-content-between align-items-center">

        <div class="title-heading-plugin d-flex flex-wrap mr-auto">
            <div class="h2 text-uppercase mb-0 m-r-10">
                <?= $titleSection ?>
            </div>
            <?php if (!$isActionIndex) { ?>
                <?= Html::a(Html::tag('span', \Yii::t('amostag', 'Vedi altri contenuti taggati')) . Html::tag('span', '', ['class' => 'icon mdi mdi-arrow-right-circle-outline']), ['index', 'tag_id' => $tag->id], [
                    'class' => 'link-all-documenti text-uppercase align-items-center small',
                    'title' => \Yii::t('amostag', 'Vedi altri contenuti taggati')
                ]) ?>
            <?php } ?>
        </div>
        <div>
            <?php
            if ($isTagActive) {
                $btnText = $removeTagText;
                $btnTitle = $removeTagTitle;
                $btnIconClass = $btnIconClassRemove;
                $btnClass = 'btn-danger-inverse tag-active';
            } else {
                $btnText = $addTagText;
                $btnTitle = $addTagTitle;
                $btnIconClass = $btnIconClassAdd;
                $btnClass = 'btn-primary tag-inactive';
            }
            echo Html::button('<span class="' . $btnIconClass . '"></span><span class="btn-text">' . $btnText . '</span>', [
                'class' => 'btn tag-action pull-right ' . $btnClass,
                'title' => $btnTitle,
                'data-toggle' => 'tooltip',
            ]); ?>
        </div>
    </div>
    <hr style="border-top: 1px solid #CCC">
</div>


<div class="<?= $isActionIndex ? 'tag-contents-index m-t-30' : 'tag-contents-detail m-t-10' ?>">
    <?php foreach ($modelParams as $modelClassname) { ?>
        <div class="tag-contents-plugin-container">
            <!--< ?= $this->render('parts/_header', [
                'viewParams' => $modelClassname['viewParams'],
                'models_classname_id' => $modelClassname['models_classname_id'],
                'tag' => $tag
            ]);
            ?>-->

            <!-- Default view params -->
            <?php
            if (empty($modelClassname['viewParams']['titleSection'])) {
                $modelClassname['viewParams']['titleSection'] = \Yii::t('amostag', '{modelLabel} con tag: {tagName}', [
                    'modelLabel' => ModelsClassname::findOne($modelClassname['models_classname_id'])->label,
                    'tagName' => '<span>' . $tag->nome . '</span>'
                ]);
            }
            if (empty($modelClassname['viewParams']['labelLinkAll'])) {
                $modelClassname['viewParams']['labelLinkAll'] = \Yii::t('amostag', 'Vedi tutto');
            }
            if (empty($modelClassname['viewParams']['titleLinkAll'])) {
                $modelClassname['viewParams']['titleLinkAll'] = \Yii::t('amostag', 'Vedi tutti i contenuti simili con tag: {tagName}', [
                    'tagName' => $tag->nome
                ]);
            } ?>

            <?= $this->render($modelClassname['view'], [
                'dataProvider' => $modelClassname['dataProvider'],
                'tag' => $tag,
                'tagContentsViewParams' => $modelClassname['viewParams'],
                'models_classname_id' => $modelClassname['models_classname_id'],
                'isActionIndex' => $isActionIndex,
                'modelSearch' => $modelClassname['modelSearch'],
                'controller' => $modelClassname['controller']
            ]); ?>
        </div>
    <?php } ?>
</div>

<!-- Modal remove tag -->
<?= $this->render('@vendor/open20/amos-core/forms/views/widgets/parts/_remove-tag-modal'); ?>

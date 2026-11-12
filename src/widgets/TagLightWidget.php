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

namespace open20\amos\tag\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\widgets\InputWidget;
use Yii;

class TagLightWidget extends InputWidget
{

    /**
     * @var AmosTag $tagModule
     */
    public $tagModule = null;

    public
        $id = 'amos-tag', // @var string $id the id of the widget container div
        $containerClass = 'tag-widget', // @var string $containerClass the class of the widget container div

        $form,
        $name = 'tagValues',
        $trees = [],
        $form_values,
        $isSearch = true,
        $hideHeader = false,
        $moduleCwh,
        $scope,
        $selectSonsOnly,
        $isFrontend = false,
        $enableAjax = false,
        $responseAjax = false,
        $treeOptions = [],
        $collapseTree = true;

    public $singleFixedTreeId = null;
    public $hideRootTrees = false;
    private static
        $allRoles;


    /**
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->tagModule = AmosTag::instance();


        parent::init();
        $this->enableAjax = false;
        if (!isset($this->form_values)) {
            $post = Yii::$app->request->post($this->model->formName());
            if (!empty($post) && key_exists($this->name, $post)) {
                $this->form_values = $post[$this->name];
            }
        }

        if (!$this->enableAjax) {
            $this->trees = $this->fetchTrees();
        } else {
            if ($this->responseAjax) {
                $this->trees = $this->fetchTrees();
            }
        }

        if (is_null($this->selectSonsOnly)) {
            $tags = \Yii::$app->getModule(AmosTag::getModuleName());
            if ($tags) {
                $this->selectSonsOnly = $tags->selectSonsOnly;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $html = '';
//        $tagsSelected = $this->form_values ? $this->getTagsSelectedFromFormValues() : $this->getTagsSelected();
        $hideHeaderInternal = (isset(\Yii::$app->params['hideTagWidgetHeader']) ? \Yii::$app->params['hideTagWidgetHeader'] : $this->hideHeader);
        $this->hideHeader = $hideHeaderInternal;
        $tagsSelected = $this->getSelectedTags();
//        pr($tagsSelected);die;
        $occurrenciesTags = $this->getOccurrencyTags();
        $labelContent = method_exists($this->model, 'getGrammar') ? $this->model->getGrammar()->getModelLabel() : AmosTag::t('amostag', "contenuti di questo tipo");

        if ($this->enableAjax) {
            $html .= $this->ajaxCall();
        }
        if (!$this->enableAjax || ($this->responseAjax && $this->enableAjax)) {
            $html .= $this->render(
                'tag_light_widget/tag_light',
                [
                    'model' => $this->model,
                    'form' => $this->form,
                    'name' => $this->name,
                    'trees' => $this->trees,
                    'modelFormName' => $this->model->formName(),
                    'is_search' => $this->isSearch,
                    'tags_selected' => $tagsSelected,
                    'tags_selected_ids' => $this->getSelectedTagsIds($tagsSelected),
                    'hideHeader' => $hideHeaderInternal,
                    'id' => $this->id,
                    'containerClass' => $this->containerClass,
                    'selectSonsOnly' => $this->selectSonsOnly,
                    'enabledAjax' => $this->enableAjax,
                    'collapseTree' => $this->collapseTree,
                    'occurrenciesTags' => $occurrenciesTags,
                    'labelContent' => $labelContent,
                    'hideRootTrees' => $this->hideRootTrees
                    //                    'isFrontend' => $this->isFrontend,

                ]
            );
        }
        return $html;

    }

    public function ajaxCall()
    {
        if ($this->responseAjax) {

        } else {
            $uniqueId = uniqid();
            \open20\amos\layout\assets\ThreeDotsAsset::register($this->view);
            $this->registerAjax($uniqueId);
            return "
                <div id='$uniqueId' class='tag-section-ajax'>
                    <div class='col-md-12'>
                        <div class='loader-3dots'>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>";
        }
        return '';
    }


    public function registerAjax($uniqueId)
    {

        $this->view->registerJsVar("classnameContent_$uniqueId", get_class($this->model));
        $this->view->registerJsVar("idContent_$uniqueId", $this->model->isNewRecord ? '' : $this->model->id);
        $this->view->registerJsVar("isSearch_$uniqueId", $this->isSearch);
        $this->view->registerJsVar("hideHeader_$uniqueId", $this->hideHeader);
        $this->view->registerJsVar("selectSonsOnly_$uniqueId", $this->selectSonsOnly);
        $this->view->registerJsVar("name_$uniqueId", $this->name);
        $this->view->registerJsVar("containerClass_$uniqueId", $this->containerClass);
        $this->view->registerJsVar("previousPost_$uniqueId", serialize(\Yii::$app->request->get()));

        $errorBlockMessage = AmosTag::t('amostag', 'Selezionare almeno 1 tag.');
        $errorTooltipTitle = AmosTag::t('amostag', 'E\' necessario scegliere almeno 1 tag');

        $js = <<<JS
        var errorBlockMessage = "$errorBlockMessage";
        var errorTooltipTitle = "$errorTooltipTitle";
        $.ajax(
            {
        url: '/tag/tag-ajax/tag-widget-light',
        type: 'get',
        data: {
            attribute: '$this->attribute',
            classname: classnameContent_$uniqueId,
            record_id: idContent_$uniqueId,
            previousPost :previousPost_$uniqueId,
            isSearch: isSearch_$uniqueId,
            hideHeader: hideHeader_$uniqueId,
            selectSonsOnly: selectSonsOnly_$uniqueId,
            containerClass: containerClass_$uniqueId,
            name: name_$uniqueId,
        },
        success: function (data) {
                $('#$uniqueId').html(data);
                $("#cwh-regola_pubblicazione").trigger('change');
            
                // Show an error message at the bottom of the section title if in the page there is a "*-regola_pubblicazione"
                // class with an error in it.
                if($('div[class*="-regola_pubblicazione"]').hasClass('has-error')){
                    $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
                    $(".tag-plugin-warning-triangle").append('<span class="tooltip-error-field"> <span title="" data-toggle="tooltip" data-placement="top" data-original-title="'+errorTooltipTitle+'"><span class="am am-alert-triangle" style="color: #a02622"> </span> </span> </span>');
                    $('div[class*="-regola_pubblicazione"]').hide();
                }
                if($('#amos-tag div').hasClass('has-error')){
                    $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
            
                }
            }
        });
JS;
        $this->view->registerJs($js);

    }

    /**
     * @return array|ActiveRecord[]
     * @throws \ReflectionException
     */
    private function fetchTrees()
    {
        $refClass = new \ReflectionClass($this->model);
        $parentClass = $refClass->getParentClass()->name;
        $trees = [];

        //recupero gli alberi
        /** @var ActiveQuery $query */
        $query = Tag::find()
            ->distinct()
            ->andWhere(['disabled' => 0])
            ->andWhere([
                'lvl' => 0,
            ]);

        if (!empty($this->singleFixedTreeId)) {
            $query->andWhere(["tag.id" => $this->singleFixedTreeId]);
        }else{
            $query->joinWith('tagModelsAuthItems')
                ->andWhere([
                'classname' => [get_class($this->model), $parentClass],
                'auth_item' => $this->getAllRoles(),
            ]);
        }

        $treeRoots = $query
            ->orderBy(['tag.nome' => SORT_DESC])
            ->all();

        // per ogni albero recupero i tag
        foreach ($treeRoots as $treeRoot) {
            $tags = Tag::find()
                ->andWhere(['root' => $treeRoot->id])
                ->andWhere(['disabled' => 0])
                ->andWhere(['<>', 'lvl', '0'])
                ->orderBy(['lft' => SORT_ASC, 'lvl' => SORT_ASC])
                ->asArray()->all();
            $trees[$treeRoot->id]['tags'] = $this->formatTree($tags);
            $trees[$treeRoot->id]['rootModel'] = $treeRoot;
        }
//        echo "<pre>".VarDumper::dumpAsString($trees,7)."</pre>";
//        die;
        return $trees;
    }

    public function formatTree($tags)
    {
        $tree = [];
        $lvl = 1;
        $currentParentPath = [];
        $currentParentId = null;
        $lastTagId = null;

        $currentParentPathString = [];
        $currentParentName = '';
        $lastNameId = null;

        foreach ($tags as $tag) {
            // se sono nello stesso livello del precedente
            if ($tag['lvl'] == $lvl) {
                if ($currentParentId === null) {
                    $tag['path'] = $currentParentPathString;
                    $tree[$tag['id']] = [
                        'lvl' => $tag['lvl'],
                        'parent_id' => null,
                        'model' => $tag,
                    ];
                } else {
                    $tag['path'] = $currentParentPathString;
                    $tree[$tag['id']] = [
                        'lvl' => $tag['lvl'],
                        'parent_id' => $currentParentId,
                        'model' => $tag,
                    ];
                }
                // se salgo di un livello
            } else if ($tag['lvl'] == ($lvl + 1)) {
                $currentParentId = $lastTagId;
                $currentParentName = $lastTagName;
                $currentParentPath[] = $currentParentId;
                $currentParentPathString[] = $currentParentName;
                $tag['path'] = $currentParentPathString;
                $tree[$tag['id']] = [
                    'lvl' => $tag['lvl'],
                    'parent_id' => $currentParentId,
                    'model' => $tag,
                ];
//                pr($currentParentPath ,'path');
                $lvl = $lvl + 1;
                // se scendo di uno o più livelli
            } else if ($tag['lvl'] < $lvl) {
                $levelDifference = $lvl - $tag['lvl'];
//                pr($levelDifference, 'sceso di livello');
                while ($levelDifference > 0) {
                    array_pop($currentParentPath);
                    array_pop($currentParentPathString);
                    $levelDifference--;
                }
//                pr($currentParentPath);
                if (empty($currentParentPath)) {
                    $currentParentId = null;
                    $currentParentName = '';
                } else {
                    $currentParentId = end($currentParentPath);
                    $currentParentName = end($currentParentPathString);
                }
                $tag['path'] = $currentParentPathString;
                $tree[$tag['id']] = [
                    'parent_id' => $currentParentId,
                    'model' => $tag,
                    'lvl' => $tag['lvl'],
                ];
                $lvl = $tag['lvl'];
            }
            $lastTagId = $tag['id'];
            $lastTagName = $tag['nome'];
        }
//        pr($tree); die;
        return $tree;
    }

    private function getAllRoles()
    {
        if (is_null(static::$allRoles)) {
            if (
                Yii::$app->getModule(
                    AmosAdmin::getModuleName()
                )
                    ->modelMap['UserProfile'] == get_class($this->model)
            ) {
                $id = $this->model['user_id'];
            } else {
                $id = \Yii::$app->getUser()->getId();
            }

            $keysRoles = array_keys(
                \Yii::$app
                    ->getAuthManager()
                    ->getRolesByUser($id)
            );

            // i want all roles that user has
            static::$allRoles = [];
            foreach ($keysRoles as $role) {
                static::$allRoles = array_unique(
                    array_merge(
                        static::$allRoles,
                        array_keys(
                            \Yii::$app->getAuthManager()->getChildRoles($role))
                    )
                );
            }
        }

        return static::$allRoles;
    }


    public function getSelectedTags()
    {
        $tagModel = $this->tagModule->createModel('Tag');
        $tagTable = $tagModel::tableName();

        /** @var EntitysTagsMm $entitysTagsMmModel */
        $entitysTagsMmModel = $this->tagModule->createModel('EntitysTagsMm');
        $entitysTagsMmTable = $entitysTagsMmModel::tableName();

        $query = new Query();
        $query->select([$tagTable . '.id', $tagTable . '.nome', $tagTable . '.root'])
            ->from($entitysTagsMmTable)
            ->innerJoin($tagTable, $tagTable . '.id = ' . $entitysTagsMmTable . '.tag_id')
            ->andWhere([$tagTable . '.deleted_at' => null])
            ->andWhere([$entitysTagsMmTable . '.deleted_at' => null])
            ->andWhere([$entitysTagsMmTable . '.classname' => get_class($this->model)])
            ->andWhere([$entitysTagsMmTable . '.record_id' => $this->model->id]);
        $tmpTags = $query->all();
        foreach ($tmpTags as $tag) {
            $tags[$tag['root']][] = $tag;
        }
        return $tags;
    }

    public function getSelectedTagsIds($modelsRoots)
    {
        $selectedTagsId = [];
        foreach ($modelsRoots as $root_id => $models) {
            foreach ($models as $model) {
                $selectedTagsId[$root_id][] = $model['id'];
            }
        }
        return $selectedTagsId;
    }

    /**
     * @return array
     */
    public function getOccurrencyTags()
    {
        $namespace = get_class($this->model);
        $root_ids = [];
        foreach ($this->trees as $root_id => $tree) {
            $root_ids[] = $root_id;
        }


        $tags = Tag::getOccurrencyTags($root_ids, $namespace);
        $occurrencies = [];
        foreach ($tags as $tag) {
            $occurrencies[$tag['id']] = $tag['occurrency'];
        }
        return $occurrencies;
    }

}
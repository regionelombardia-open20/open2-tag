<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\controllers;

use open20\amos\tag\utility\TagUtility;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\tag\models\Tag;
use open20\amos\tag\models\search\TagSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\Url;
use open20\amos\tag\AmosTag;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use Yii;

class ManagerLightController extends CrudController {

    public $layout = 'main';

    public function init() {
        $this->setModelObj(new Tag());
        $this->setModelSearch(new TagSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    public function beforeAction($action) {
        $titleSection = AmosTag::t('amostag', 'Tag Manager');

        $this->view->params = [
            'titleSection' => $titleSection,
            'hideCreate' => true,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }
        // other custom code here
        return true;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(
                        parent::behaviors(),
                        [
                            'access' => [
                                'class' => AccessControl::class,
                                'rules' => [
                                    [
                                        'allow' => true,
                                        'actions' => [
                                            'index',
                                            'detail-tree',
                                            'create-tree',
                                            'update-tree',
                                            'info-tags',
                                            'hide-show',
                                            'multiple-hide-show',
                                            'search-tag',
                                        ],
                                        'roles' => ['TAG_MANAGER_LIGHT']
                                    ],
                                ]
                            ],
                            'verbs' => [
                                'class' => VerbFilter::class,
                                'actions' => [
                                    'delete' => ['post', 'get']
                                ]
                            ]
                        ]
        );

        return $behaviors;
    }

    public function actionIndex($layout = NULL) {
        $this->view->params['titleSection'] = AmosTag::t('amostag', 'Gestione tag');
        $this->view->params['subTitleSection'] = '';
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams(), true, 0));
        return parent::actionIndex($layout);
    }

    /**
     * funzione che genera l'albero a sinistra della pagina index
     * @param $id del ramo padre
     * @return render html
     */
    public function actionDetailTree($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Tag::findOne($id);
        $folderList = TagUtility::getFoldersTagList($model);
        $folderCount = count($folderList);

        $occurrencyTags = Tag::getOccurrencyTags([$model->root]);
        $occurrencies = [];
        foreach ($occurrencyTags as $ocTags) {
            $occurrencies[$ocTags['id']] = $ocTags['occurrency'];
        }

        $lvl = $model->lvl + 1;
        $children = TagUtility::getChildrensForTag($model);

        $html = $this->renderPartial('left_section', [
            'model' => $model,
            'folderList' => $folderList,
            'folderCount' => $folderCount,
            'occurrencies' => $occurrencies,
            'children' => $children,
            'lvl' => $lvl
        ]);

        $htmlBreadcrumb = $this->renderPartial('parts/_tag_breadcrumb', [
            'folderList' => $folderList,
            'folderCount' => $folderCount,
        ]);

        return [
            'htmlTree' => $html,
            'htmlBreadcrumb' => $htmlBreadcrumb,
        ];
    }

    /**
     * crea tag data la posizione in cui ti trovi
     * @param $id del padre
     * @return render html
     */
    public function actionCreateTree($id) {
        $model = New Tag();
        $parent = Tag::findOne($id);

        if ($model->load(Yii::$app->request->post())) {


            $model->active = 1;
            $model->visible = 1;
            $model->disabled = 0;
            $model->readonly = 0;
            $model->movable_u = 1;
            $model->movable_d = 1;
            $model->movable_l = 1;
            $model->movable_r = 1;

            if (!is_null($parent)) {
                $model->appendTo($parent);
            }

            if (!$model->save()) {
                return false;
            } else {
                return true;
            }
        }
        return $this->renderAjax('_form', [
                    'model' => $model,
                    'id' => $id,
                    'parent_id' => $parent->id,
        ]);
    }

    /**
     * aggiorna le informazioni del tag corrente
     * @param $id del tag da modificare
     * @return render html
     */
    public function actionUpdateTree($id) {
        $model = Tag::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->movable_u = 1;
            $model->movable_d = 1;
            $model->movable_l = 1;
            $model->movable_r = 1;

            if (!$model->save()) {

                return false;
            } else {

                return true;
            }
        }
        $parent = Tag::find()->andWhere(['<', 'lft', $model->lft])->andWhere(['>', 'rgt', $model->rgt])->andWhere(['root' => $model->root])->one();
        return $this->renderAjax('_form', [
                    'model' => $model,
                    'id' => $id,
                    'parent_id' => $parent->id,
        ]);
    }

    /** funzione che recuperare le informazioni del tag o dei tack checkati e rendirizza la parte di destra della pagina index
     * @param array $id id o array di id selezionati
     * @return render html
     */
    public function actionInfoTags() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('ids', []);

        $models = Tag::find()->where(['id' => $ids])->all();
        $totalOccurency = 0;
        $modelRoots = [];
        $all_active = 1;
        $all_disabled = 1;

        foreach ($models as $model) {
            $modelRoots[] = $model->root;
            if ($model->disabled) {
                $all_active = 0;
            } else {
                $all_disabled = 0;
            }
            $level = $model->lvl - 1;
            if (!$parent) {
                $parent = Tag::find()->andWhere(['<', 'lft', $model->lft])->andWhere(['lvl' => $level])->andWhere(['>', 'rgt', $model->rgt])->andWhere(['root' => $model->root])->one();
            }
        }

        $occurrencyTags = Tag::getOccurrencyTags($modelRoots, null, $ids);
        foreach ($occurrencyTags as $ocTags) {
            $totalOccurency += $ocTags['occurrency'];
        }

        $query = CwhTagOwnerInterestMm::find();
        $query->andWhere([
            'interest_classname' => 'simple-choice',
            'classname' => 'open20\amos\admin\models\UserProfile',
        ])->andWhere(['tag_id' => $ids])
        ;
        $userInterestsCount = $query->count();

        return $this->renderPartial('right_section', [
                    'models' => $models,
                    'countSelected' => count($ids),
                    'ids' => $ids,
                    'occurrencies' => $totalOccurency,
                    'userInterestsCount' => $userInterestsCount,
                    'parent_id' => $parent->id,
                    'all_active' => $all_active,
                    'all_disabled' => $all_disabled,
        ]);
    }

    /**
     * funzione che disabilita o riabilita un tag e tutti i figli se ne ha
     * @param $id del tag da disabilitare o abilitare
     * @return render html
     */
    public function actionHideShow($id) {
        $model = Tag::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                return false;
            } else {
                Tag::updateAll(['disabled' => $model->disabled],
                        [
                            'and',
                            ['root' => $model->root],
                            ['>', 'lft', $model->lft],
                            ['<', 'rgt', $model->rgt]
                ]);
                return true;
            }
        }
        $children = TagUtility::getChildrensForTag($model);
        $nOccurrencies = Tag::totalOccurency($model);
        $level = $model->lvl - 1;
        $parent = Tag::find()->andWhere(['<', 'lft', $model->lft])->andWhere(['lvl' => $level])->andWhere(['>', 'rgt', $model->rgt])->andWhere(['root' => $model->root])->one();

        return $this->renderAjax('parts/_hideshow', [
                    'model' => $model,
                    'id' => $id,
                    'nOccurrencies' => $nOccurrencies,
                    'parent_id' => $parent->id,
                    'children' => $children,
        ]);
    }

    /**
     * funzione che disabilita o riabilita una lista di tag e tutti i figli che hanno se ne hanno
     * @param array $id dei tag da disabilitare o abilitare
     * @return render html
     */
    public function actionMultipleHideShow() {

        if (Yii::$app->request->post()) {

            $ids = Yii::$app->request->post()['ids'];
            $disabled = Yii::$app->request->post()['disabled'];
            $models = Tag::findAll($ids);
            foreach ($models as $model) {
                Tag::updateAll(['disabled' => $disabled],
                        [
                            'and',
                            ['root' => $model->root],
                            ['>=', 'lft', $model->lft],
                            ['<=', 'rgt', $model->rgt]
                ]);
            }
            return true;
        } else {

            $ids = Yii::$app->request->get('id', []);
            $disabled = Yii::$app->request->get('disabled');
            $parent_id = Yii::$app->request->get('parent_id');
            $models = Tag::findAll($ids);

            return $this->renderAjax('parts/_multiplehideshow', [
                        'ids' => $ids,
                        'disabled' => $disabled,
                        'parent_id' => $parent_id,
            ]);
        }
    }

    /**
     * @param $q
     * @return array[]
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSearchTag($q = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!empty($q)) {
            $data = Tag::find()
                    ->andWhere(['like', 'nome', $q])
                    ->andWhere(['only_admin' => 0])
                    ->all();
            if (!empty($data)) {
                $out['results'] = [];
                $limit = 0;
                foreach ($data as $result) {

                    $model = Tag::findOne($result->id);

                    $level = $model->lvl - 1;
                    $parent = Tag::find()->andWhere(['<', 'lft', $model->lft])->andWhere(['lvl' => $level])->andWhere(['>', 'rgt', $model->rgt])->andWhere(['root' => $model->root])->andWhere(['only_admin' => 0])->one();
                    $root = Tag::find()->andWhere(['<', 'lft', $model->lft])->andWhere(['lvl' => 0])->andWhere(['>', 'rgt', $model->rgt])->andWhere(['root' => $model->root])->andWhere(['only_admin' => 0])->one();
                    $path = '/ ' . $model->getPath(false);

                    if ($parent && $root) {
                        $out['results'][] = ['id' => $result->id, 'text' => $result->nome . ' - ' . $path, 'parent_id' => $parent->id];
                        $limit++;
                        if ($limit > 5) {
                            return $out;
                        }
                    }
                }
            }
        }
        return $out;
    }

}

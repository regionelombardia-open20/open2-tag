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

namespace open20\amos\tag\controllers;


use open20\amos\core\components\AmosView;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\ContentModel;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventSearch;
use open20\amos\news\AmosNews;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


/**
 * Class TagContentsController
 * @package open20\amos\tag\controllers
 */
class TagContentsController extends BackendController
{
    /** @var $moduleCwh \open20\amos\cwh\AmosCwh */
    public $moduleCwh;

    /** @var $moduleTag AmosTag */
    public $moduleTag;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'detail',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->moduleTag = AmosTag::instance();
        $this->moduleCwh = \Yii::$app->getModule('cwh');

        // Reset scope
        if ($this->moduleCwh) {
            $this->moduleCwh->resetCwhScopeInSession();
        }

        //$this->setUpLayout('main');
    }

    /**
     * @param $tag_id int
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($tag_id)
    {
        $tag = Tag::findOne($tag_id);
        if (empty($tag)) {
            throw new NotFoundHttpException('Tag not found');
        }

        \Yii::$app->view->params['containerFullWidth'] = true;
        \Yii::$app->view->params['breadcrumbsClassContainer'] = 'container';

        $modelParams = [];
        $tagContentsModelsEnabled = $this->moduleTag->tagContentsModelsEnabled;

        foreach ($tagContentsModelsEnabled as $modelClassname) {
            if (class_exists($modelClassname)) {
                /** @var \yii\base\Model $modelContent */
                $modelContent = new $modelClassname();
                if (!empty($modelContent) && $modelContent instanceof ContentModel) {
                    if (in_array('open20\amos\core\interfaces\TagContentsModelInterface', class_implements($modelContent))) {
                        /** @var $controllerContent \open20\amos\core\controllers\CrudController */
                        $controllerContent = $modelContent->getTagContentsControllerObj();
                        /** @var $modelSearch ContentModel */
                        $modelSearch = $controllerContent->getModelSearch();
                        $modelSearch->setNotifier(new NotifyWidgetDoNothing());
                        if (in_array('open20\amos\core\interfaces\TagContentsModelSearchInterface', class_implements($modelSearch))) {
                            $queryParams = Yii::$app->request->getQueryParams();
                            $queryParams[$modelSearch->formName()]['tagValues'] = [$tag->root => $tag->id];
                            $queryParams['isActionIndex'] = true;

                            $modelParams[$modelClassname]['dataProvider'] = $modelSearch->searchTagContents($queryParams, null);
                            $modelParams[$modelClassname]['view'] = $modelContent->getTagContentsView();
                            $modelParams[$modelClassname]['viewParams'] = $modelContent->getTagContentsViewParams($tag);
                            $models_classname = ModelsClassname::findOne(['classname' => $modelClassname]);
                            if (!empty($models_classname)) {
                                $modelParams[$modelClassname]['models_classname_id'] = $models_classname->id;
                            } else {
                                throw new InvalidConfigException('Model "' . $modelClassname . '" must be present in "models_classname" table to be used in "tagContentsModelsEnabled" tag configuration param.');
                            }
                        } else {
                            throw new InvalidConfigException('Model "' . $modelSearch . '" must implement "open20\amos\core\interfaces\TagContentsModelSearchInterface".');
                        }
                    } else {
                        throw new InvalidConfigException('Model "' . $modelClassname . '" must implement "open20\amos\core\interfaces\TagContentsModelInterface" to be used in "tagContentsModelsEnabled" tag configuration param.');
                    }
                } else {
                    throw new InvalidConfigException($modelClassname . ' not found or not instance of ' . ContentModel::className());
                }
            }
        }

        return $this->render('index', [
            'tag' => $tag,
            'modelParams' => $modelParams,
            'isActionIndex' => true,
        ]);
    }

    /**
     * @param $tag_id int
     * @param $models_classname_id int
     * @return string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionDetail($tag_id, $models_classname_id)
    {
        $tag = Tag::findOne($tag_id);
        if (empty($tag)) {
            throw new \yii\web\NotFoundHttpException('Tag not found');
        }
        $models_classname = ModelsClassname::findOne($models_classname_id);
        if (empty($models_classname)) {
            throw new \yii\web\NotFoundHttpException('Model classname not found in models_classname');
        }

        $modelParams = [];
        $modelClassname = $models_classname->classname;
        $modelContent = new $modelClassname();

        if (!empty($modelContent) && $modelContent instanceof ContentModel) {
            if (in_array('open20\amos\core\interfaces\TagContentsModelInterface', class_implements($modelContent))) {
                /** @var $controllerContent \open20\amos\core\controllers\CrudController */
                $controllerContent = $modelContent->getTagContentsControllerObj();
                Yii::$app->params['customControllerObj'] = $controllerContent;
                /** @var $modelSearch ContentModel */
                $modelSearch = $controllerContent->getModelSearch();
                $modelSearch->setNotifier(new NotifyWidgetDoNothing());
                if (in_array('open20\amos\core\interfaces\TagContentsModelSearchInterface', class_implements($modelSearch))) {
                    $queryParams = Yii::$app->request->getQueryParams();
                    $queryParams[$modelSearch->formName()]['tagValues'] = [$tag->root => $tag->id];

                    $modelParams[$modelClassname]['dataProvider'] = $modelSearch->searchTagContents($queryParams, null);
                    $modelParams[$modelClassname]['view'] = $modelContent->getTagContentsView();
                    $modelParams[$modelClassname]['viewParams'] = $modelContent->getTagContentsViewParams($tag);
                    $modelParams[$modelClassname]['controller'] = $controllerContent;
                    $modelParams[$modelClassname]['modelSearch'] = $modelSearch;
                    $modelParams[$modelClassname]['models_classname_id'] = $models_classname_id;
                } else {
                    throw new InvalidConfigException('Model "' . $modelSearch . '" must implement "open20\amos\core\interfaces\TagContentsModelSearchInterface".');
                }
            } else {
                throw new InvalidConfigException('Model "' . $modelClassname . '" must implement "open20\amos\core\interfaces\TagContentsModelInterface" to be used in "tagContentsModelsEnabled" tag configuration param.');
            }
        } else {
            throw new InvalidConfigException($modelClassname . ' not found or not instance of ' . ContentModel::className());
        }


        return $this->render('index', [
            'tag' => $tag,
            'modelParams' => $modelParams,
        ]);
    }

}
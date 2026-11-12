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

namespace open20\amos\tag\controllers;

use yii\base\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class ManagerController extends Controller {

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setUpLayout();
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
                                            'is-root-only-admin',
                                        ],
                                        'roles' => ['TAG_EDITOR']
                                    ],
                                    [
                                        'allow' => true,
                                        'actions' => [
                                            'autocomplete-free-tag'
                                        ],
                                        'roles' => ['BASIC_USER', 'ADMIN', 'AMMINISTRATORE_TAG']
                                    ]
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

    /**
     * @return mixed
     */
    public function actionIndex() {
        $this->setUpLayout("form");
        return $this->render('index');
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null) {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }

        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            $this->layout = '@vendor/open20/amos-core/views/layouts/'
                    . (!empty($layout) ? $layout : $this->layout);
            return true;
        }

        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    public function actionAutocompleteFreeTag($id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $term = filter_input(INPUT_GET, 'term');
        if (!empty($term)) {
            $data = \open20\amos\tag\models\TagFree::find()
                    ->andWhere(['like', 'tag', $term])
                    ->select('tag')
                    ->limit(60)
                    ->column();

            return $data;
        }

        return $out;
    }

    /**
     * @return bool
     */
    public function actionIsRootOnlyAdmin() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = \Yii::$app->request->post();
        $tagId = $post['tagId'];
        if ($tagId && !\Yii::$app->user->can('AMMINISTRATORE_TAG')) {
            $tag = \open20\amos\tag\models\Tag::findOne($tagId);
            if (!empty($tag)) {
                $rootId = $tag->root;
                $tagRoot = \open20\amos\tag\models\Tag::findOne($rootId);
                if ($tagRoot) {
                    return $tagRoot->only_admin;
                }
            }
        }
        return false;
    }

}

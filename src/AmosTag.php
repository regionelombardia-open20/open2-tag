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

namespace open20\amos\tag;

use open20\amos\core\module\AmosModule;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\record\Record;
use open20\amos\tag\widgets\icons\WidgetIconTag;
use open20\amos\tag\widgets\icons\WidgetIconTagManager;
use Yii;

/**
 * Class AmosTag
 * @package open20\amos\tag
 */
class AmosTag extends AmosModule implements CmsModuleInterface, SearchModuleInterface {

    /**
     * 
     * @var type
     */
    public $controllerNamespace = 'open20\amos\tag\controllers';

    /**
     * @var string
     */
    public $postKey = 'Tag';

    /**
     * @var array
     */
    public $modelsEnabled = [];

    /**
     * 
     * @var type
     */
    public $behaviors = [
        'open20\amos\core\behaviors\TaggableBehavior'
    ];

    /**
     * 
     * @var type
     */
    public $name = 'Tag';

    /**
     * 
     * @var type
     */
    public $selectSonsOnly = true;
    
    /**
     * E.g.: ['open20\amos\news\models\News' => 1]
     * @var type
     */
    public $modelsTagFree = [];

    public $enableTagLight = false;

    /**
     * E.g.: ['open20\amos\news\models\News', '...']
     * @var string[] $tagContentsModelsEnabled Array containing the models for which to show contents in TagContentsController view
     */
    public $tagContentsModelsEnabled = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        Record::$modulesChainBehavior[] = 'tag';
        Yii::setAlias(
                '@open20/amos/'
                . static::getModuleName()
                . '/controllers',
                __DIR__
                . '/controllers'
        );

        //aggiunge le configurazioni trovate nel file config/config.php
        Yii::configure(
                $this,
                require(
                __DIR__
                . DIRECTORY_SEPARATOR
                . 'config'
                . DIRECTORY_SEPARATOR
                . 'config.php'
                )
        );
    }

    /**
     *
     */
    public function bootstrap() {
        $treeManagerModule = Yii::$app->getModule('treemanager');

        if (!$treeManagerModule) {
            Yii::$app->setModule('treemanager', $this->getModule('treemanager'));
        }
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName() {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
            WidgetIconTagManager::class,
            WidgetIconTag::class
        ];
    }

    /**
     * Chiave che verrà spedita in post
     *
     * @return string
     */
    public function getPostKey() {
        return $this->postKey;
    }

    /**
     * @param string $postKey
     */
    public function setPostKey($postKey) {
        $this->postKey = $postKey;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels() {
        return [
            'EntitysTagsMm' => __NAMESPACE__ . '\models\EntitysTagsMm',
            'Tag' => __NAMESPACE__ . '\models\Tag',
            'TagModelsAuthItemsMm' => __NAMESPACE__ . '\models\TagModelsAuthItemsMm',
            'TagSearch' => __NAMESPACE__ . '\models\search\TagSearch',
        ];
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1) {
        $menu = "";
        $app = \Yii::$app;
        if (!$app->user->isGuest) {
            if (Yii::$app->user->can('AMMINISTRATORE_TAG')) {
                $menu .= $this->addFrontEndMenu(
                        AmosTag::t('amostag', '#menu_front_tags'),
                        AmosTag::toUrlModule('/manager'), $dept);
            }
        }

        return $menu;
    }

    /**
     * 
     * @return type
     */
    public static function getModelClassName() {
        return AmosTag::instance()->model('Tag');
    }

    /**
     * 
     * @return type
     */
    public static function getModelSearchClassName() {
        return AmosTag::instance()->model('TagSearch');
    }

    /**
     * 
     * @return type
     */
    public static function getModuleIconName() {
        return null;
    }

}

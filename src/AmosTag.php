<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

namespace lispa\amos\tag;

use lispa\amos\core\module\AmosModule;
use lispa\amos\core\record\Record;
use lispa\amos\tag\widgets\icons\WidgetIconTag;
use lispa\amos\tag\widgets\icons\WidgetIconTagManager;
use Yii;

class AmosTag extends AmosModule
{
    public $controllerNamespace = 'lispa\amos\tag\controllers';
    /**
     * @var string
     */
    public $postKey = 'Tag';
    /**
     * @var array
     */
    public $modelsEnabled = [

    ];
    public $behaviors = [
        'lispa\amos\core\behaviors\TaggableBehavior'
    ];

    public $name = 'Tag';

    public function init()
    {
        parent::init();
        Record::$modulesChainBehavior[] = 'tag';
        Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
        //aggiunge le configurazioni trovate nel file config/config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    public function bootstrap()
    {
        $treeManagerModule = Yii::$app->getModule('treemanager');
        if(!$treeManagerModule){
            Yii::$app->setModule('treemanager', $this->getModule('treemanager'));
        }

    }

    public static function getModuleName()
    {
        return 'tag';
    }

    public function getWidgetGraphics()
    {

    }

    public function getWidgetIcons()
    {
        return [
            WidgetIconTagManager::className(),
            WidgetIconTag::className()
        ];
    }

    /**
     * Chiave che verrà spedita in post
     *
     * @return string
     */
    public function getPostKey()
    {
        return $this->postKey;
    }

    /**
     * @param string $postKey
     */
    public function setPostKey($postKey)
    {
        $this->postKey = $postKey;
    }

    protected function getDefaultModels()
    {
        return [
            'Tag' => __NAMESPACE__ . '\\' . 'models\\Tag',
        ];
    }
}

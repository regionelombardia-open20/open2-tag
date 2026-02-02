<?php

namespace open20\amos\tag\assets;

use yii\web\AssetBundle;

class TagContentsAsset extends AssetBundle
{

    public $sourcePath = '@vendor/open20/amos-tag/src/assets/web';


    public $css = [
        'less/tag-contents.less',
    ];


    public $js = [

    ];


    public $depends = [
        // 'yii\web\JqueryAsset'
    ];

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

}
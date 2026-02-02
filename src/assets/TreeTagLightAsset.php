<?php

namespace open20\amos\tag\assets;

use yii\web\AssetBundle;

class TreeTagLightAsset extends AssetBundle
{

    public $sourcePath = '@vendor/open20/amos-tag/src/assets/web';


    public $css = [
        'less/core-tree-view.less',
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
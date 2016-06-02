<?php

namespace inblank\showroom\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $sourcePath = '@inblank/showroom/assets/files';
    public $css = [
        'css/frontend.css',
    ];
    public $js = [
        'js/frontend.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

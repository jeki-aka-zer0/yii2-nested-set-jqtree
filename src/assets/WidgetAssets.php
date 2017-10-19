<?php

namespace jekiakazer0\nsjqtree\src\assets;

use yii\web\AssetBundle;

class WidgetAssets extends AssetBundle
{
    public $sourcePath = '@vendor/jeki-aka-zer0/yii2-nsjqtree/src/media';

    public $js = [
        'js/nsjqtree'.(YII_ENV === 'prod' ? '.min' : '').'.jquery.js',
    ];

    public $css = [
        'css/jqtree.bootstrap'.(YII_ENV === 'prod' ? '.min' : '').'.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        JQTreeAssets::class,
    ];
}

<?php

namespace jekiakazer0\nsjqtree;

use yii\web\AssetBundle;

class TreeAssets extends AssetBundle
{
    public $sourcePath = '@vendor/jeki-aka-zer0/yii2-nsjqtree/assets';

    /*public $css = [
        'css/jqtree.css',
    ];*/

    public $js = [
//        'js/tree.jquery.js',
        'js/nsjqtree.jquery.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
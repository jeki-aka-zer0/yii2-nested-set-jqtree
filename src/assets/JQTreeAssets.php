<?php

namespace jekiakazer0\nsjqtree\src\assets;

use yii\web\AssetBundle;

class JQTreeAssets extends AssetBundle
{
    public $sourcePath = '@bower/jqtree';

    public $css = [
        'jqtree.css',
    ];

    public $js = [
        'tree.jquery.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

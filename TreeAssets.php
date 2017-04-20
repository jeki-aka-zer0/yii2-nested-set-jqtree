<?php

namespace jekiakazer0\nsjqtree;

use yii\web\AssetBundle;

class TreeAssets extends AssetBundle
{
    public $sourcePath = '@vendor/jeki-aka-zer0/yii2-nsjqtree/assets';

    public $js = [
        'js/nsjqtree.jquery.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'jekiakazer0\nsjqtree\JQTreeAssets',
    ];
}
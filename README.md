jqTree.js widget for Yii2
============================

Renders a [jqTree.js plugin](https://github.com/mbraak/jqTree) widget.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require jeki-aka-zer0/yii2-nsjqtree "*"
```
or add

```json
"jeki-aka-zer0/yii2-nsjqtree" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----

```php
<?php
use jekiakazer0\nsjqtree\Tree;

$dataTree = [
    [
        'id' => 1,
        'label' => '<a href="#">node 1</a>',
        'depth' => 0,
    ],
    [
        'id' => 3,
        'label' => '<a href="#">child 1</a>',
        'depth' => 1,
    ],
    [
        'id' => 4,
        'label' => '<a href="#">child 2</a>',
        'depth' => 1,
    ],
    [
        'id' => 2,
        'label' => '<a href="#">node2</a>',
        'depth' => 0,
    ],
];

echo Tree::widget([
    'data' => $data,
    'selectedNode' => 3,
    'dragAndDropUrl' => Url::to(['dnd']),
]) ?>
```

> [Get√Yii](http://www.getyii.com)
<i>Web development has never been so fun!</i>
[www.getyii.com](http://www.getyii.com)
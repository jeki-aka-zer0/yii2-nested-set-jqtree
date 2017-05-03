jqTree.js widget for Yii2
============================

Renders a [nested tree](https://github.com/creocoder/yii2-nested-sets) with a [jqTree.js plugin](https://github.com/mbraak/jqTree) widget.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require jeki-aka-zer0/yii2-nsjqtree "1.0.x-dev"
```
or add

```json
"jeki-aka-zer0/yii2-nsjqtree" : "1.0.x-dev"
```

to the require section of your application's `composer.json` file.

Configuring
-----

```php

class CategoryController extends Controller
{
    public function actions()
    {
        return [
            'dnd' => [
                'class' => 'jekiakazer0\nsjqtree\DndAction'
            ],
        ];
    }
}
```



Usage
-----

```php
<?php

echo jekiakazer0\nsjqtree\Tree::widget([
    'modelName' => ArticleCategory::className(),
]);
```

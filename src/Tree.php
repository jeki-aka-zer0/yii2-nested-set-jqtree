<?php

namespace jekiakazer0\nsjqtree\src;

use jekiakazer0\nsjqtree\src\assets\JQTreeAssets;
use jekiakazer0\nsjqtree\src\assets\WidgetAssets;
use jekiakazer0\nsjqtree\src\helpers\config\MainConfig;
use jekiakazer0\nsjqtree\src\helpers\JsonNestedSetsHelper;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap\Widget;

class Tree extends Widget
{
    public $theme = WidgetAssets::class;
    public $modelName;
    public $nodes;
    public $selectedNodeId;
    public $keyCategory = self::KEY_CATEGORY;
    public $dragAndDropUrl;
    /**
     * @var MainConfig
     */
    public $config;

    private const TREE_CLASS = 'ns-jqtree-js';
    private const KEY_CATEGORY = 'category';

    public function init()
    {
        $this->initParameters();
        $this->initOptions();
    }

    private function initParameters()
    {
        if ($this->nodes === null && $this->modelName === null) {
            throw new InvalidConfigException('You must set data or model name.');
        } elseif (!$this->keyCategory) {
            throw new InvalidConfigException('KeyCategory parameter is required.');
        }

        if ($this->config === null) {
            $this->config = (new MainConfig)
                ->keyCategory($this->keyCategory);
        }

        if ($this->nodes === null) {
            $this->getNodes();
        }

        $this->config
            ->nodes($this->nodes);
    }

    private function getNodes()
    {
        $keys = $this->config->keys;
        $select = [
            $keys->id,
            $keys->label,
            $keys->left,
            $keys->right,
            $keys->depth,
        ];
        if ($keys->status) {
            $select[] = $keys->status;
        }

        $modelName = $this->modelName;
        $this->nodes = $modelName::find()
            ->select($select)
            ->orderBy($keys->left)
            ->asArray()
            ->all();
    }

    private function initOptions()
    {
        $this->options = (array)$this->options;

        // class
        if (!isset($this->options['class'])) {
            $this->options['class'] = self::TREE_CLASS;
        }

        // data
        if (!isset($this->options['data'])) {
            $this->options['data'] = [];
        } else {
            $this->options['data'] = (array)$this->options['data'];
        }

        // selected mode
        if (($selectedNodeId = $this->getSelectedNodeId()) !== null) {
            $this->options['data']['selected-node-id'] = $selectedNodeId;
        }

        // dnd
        if ($this->dragAndDropUrl) {
            $this->options['data']['dnd-url'] = $this->dragAndDropUrl;
        }
    }

    private function getSelectedNodeId()
    {
        if ($this->selectedNodeId === null) {
            $this->selectedNodeId = \Yii::$app->request->get($this->keyCategory);
        }

        return $this->selectedNodeId;
    }

    public function run()
    {
        if ($this->nodes) {
            $this->registerAssets();
            $this->registerJs();
            $this->renderLayout();
        }
    }

    private function registerAssets()
    {
        /**
         * @var \yii\web\AssetBundle $asset
         */
        $view = $this->getView();
        $asset = $this->theme;
        JQTreeAssets::register($view);
        $asset::register($view);
    }

    private function registerJs()
    {
        $dragAndDrop = $this->dragAndDropUrl ? 'true' : 'false';
        $treeJson = (new JsonNestedSetsHelper($this->config))
            ->renderTree();

        $js = <<<JS
$('.{$this->options['class']}').tree({
    data: {$treeJson},
    autoEscape: false,
    dragAndDrop: {$dragAndDrop}
});
JS;
        $this->getView()
            ->registerJs($js);
    }

    private function renderLayout()
    {
        echo Html::tag('div', '', $this->options).PHP_EOL;
    }
}

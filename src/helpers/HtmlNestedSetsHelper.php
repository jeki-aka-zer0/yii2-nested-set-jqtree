<?php

namespace jekiakazer0\nsjqtree\src\helpers;

use jekiakazer0\nsjqtree\src\helpers\config\HtmlConfig;
use yii\helpers\Html;

class HtmlNestedSetsHelper extends NestedSetsHelper
{
    /**
     * @var HtmlConfig
     */
    protected $config;

    protected function getOpenNode(): string
    {
        return Html::beginTag($this->config->tagNode, $this->getNodeHtmlOptions());
    }

    protected function getCloseNode(): string
    {
        return Html::endTag($this->config->tagNode);
    }

    protected function getOpenLevel(): string
    {
        return Html::beginTag($this->config->tagLevel).$this->getOpenNode();
    }

    protected function getCloseLevel(): string
    {
        return Html::endTag($this->config->tagLevel);
    }

    protected function levelContinues(): string
    {
        return parent::levelContinues().$this->getOpenNode();
    }

    protected function levelFell(): string
    {
        return parent::levelFell().$this->getOpenNode();
    }

    private function getNodeHtmlOptions(): array
    {
        $nodeHtmlOptions = $this->config->nodeHtmlOptions;

        if (!isset($nodeHtmlOptions['class'])) {
            $nodeHtmlOptions['class'] = [];
        }

        $nodeHtmlOptions['class'][] = "{$this->config->nodeLevelClass}-{$this->level}";
        $nodeHtmlOptions['class'][] = "{$this->config->nodeLevelClass}-origin-{$this->node[$this->config->keys->depth]}";

        if ((int)$this->node[$this->config->keys->left] !== ($this->node[$this->config->keys->right] - 1)) {
            $nodeHtmlOptions['class'][] = $this->config->nodeHasChildrenClass;
        }

        if ($this->config->keys->status && isset($this->node[$this->config->keys->status]) && !$this->node[$this->config->keys->status]) {
            $nodeHtmlOptions['class'][] = $this->config->nodeDisabledClass;
        }

        return $nodeHtmlOptions;
    }
}

<?php

namespace jekiakazer0\nsjqtree\src\helpers;

use jekiakazer0\nsjqtree\src\helpers\config\MainConfig;
use yii\helpers\Html;

abstract class NestedSetsHelper
{
    abstract protected function getOpenNode(): string;

    abstract protected function getCloseNode(): string;

    abstract protected function getOpenLevel(): string;

    abstract protected function getCloseLevel(): string;

    /**
     * @var MainConfig
     */
    protected $config;

    public function __construct(MainConfig $config)
    {
        $this->config = $config;
    }

    protected $currentLevel = 0;
    protected $counter = 0;
    protected $level = 0;
    protected $node;

    public function renderTree():? string
    {
        if (!$nodes = $this->config->nodes) {
            return null;
        }

        $result = '';

        foreach ($nodes as $node) {
            $this->node = $node;
            $this->level = $this->getNodeLevel();

            if ($this->isLevelContinues()) {
                $result .= $this->levelContinues();
            } elseif ($this->isLevelRose()) {
                $result .= $this->levelRose();
            } elseif ($this->isLevelFell()) {
                $result .= $this->levelFell();
            }

            $result .= $this->getNodeLabel();

            $this->counter++;
        }

        if ($result) {
            $result .= str_repeat($this->getCloseNode().$this->getCloseLevel(), $this->level);
        }

        return $result;
    }

    protected function levelContinues(): string
    {
        return $this->getCloseNode();
    }

    protected function levelRose(): string
    {
        $result = $this->getOpenLevel();
        $this->currentLevel += ($this->level - $this->currentLevel);
        return $result;
    }

    protected function levelFell(): string
    {
        $multiplier = $this->currentLevel - $this->level;
        $this->currentLevel -= $multiplier;
        return str_repeat($this->getCloseNode().$this->getCloseLevel(), $multiplier).$this->getCloseNode();
    }

    private function getNodeLevel()
    {
        return $this->node[$this->config->keys->depth] - $this->getCoefficient();
    }

    private $coefficient;

    private function getCoefficient(): int
    {
        if ($this->coefficient === null) {
            $this->coefficient = $this->config->beginFromFirst
                ? $this->node[$this->config->keys->depth] - 1
                : 0;
        }

        return $this->coefficient;
    }

    private function isLevelContinues(): bool
    {
        return $this->level === $this->currentLevel && $this->counter > 0;
    }

    private function isLevelRose(): bool
    {
        return $this->level > $this->currentLevel;
    }

    private function isLevelFell(): bool
    {
        return $this->level < $this->currentLevel;
    }

    protected function getNodeLabel(): string
    {
        $label = $this->config->autoEscape
            ? Html::encode($this->node[$this->config->keys->label])
            : $this->node[$this->config->keys->label];

        $url = array_merge($this->config->linkUrl, [$this->config->keyCategory => $this->node[$this->config->keys->id]]);

        return Html::a($label, $url, $this->config->linkHtmlOptions);
    }
}

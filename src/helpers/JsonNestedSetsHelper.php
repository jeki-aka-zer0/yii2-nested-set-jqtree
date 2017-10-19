<?php

namespace jekiakazer0\nsjqtree\src\helpers;

class JsonNestedSetsHelper extends NestedSetsHelper
{
    private const OPEN_NODE = '{';
    private const CLOSE_NODE = '}';
    private const OPEN_LEVEL = '[';
    private const CLOSE_LEVEL = ']';

    protected function getOpenNode(): string
    {
        return self::OPEN_NODE;
    }

    protected function getCloseNode(): string
    {
        return self::CLOSE_NODE;
    }

    protected function getOpenLevel(): string
    {
        return self::OPEN_LEVEL;
    }

    protected function getCloseLevel(): string
    {
        return self::CLOSE_LEVEL;
    }

    protected function levelRose(): string
    {
        return ($this->level === 1 ? '' : ',children:').parent::levelRose();
    }

    protected function levelContinues(): string
    {
        return parent::levelContinues().',';
    }

    protected function levelFell(): string
    {
        return parent::levelFell().',';
    }

    protected function getNodeLabel(): string
    {
        return "{label:'".parent::getNodeLabel()."',id:{$this->node[$this->config->keys->id]}";
    }
}

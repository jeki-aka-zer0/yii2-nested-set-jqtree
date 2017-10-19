<?php

namespace jekiakazer0\nsjqtree\src\helpers\config;

/**
 * Class MainConfig
 * @package core\helpers\ns\config
 *
 * @property string $tagNode
 * @property string $tagLevel
 * @property array $nodeHtmlOptions
 * @property string $nodeHasChildrenClass
 * @property string $nodeLevelClass
 * @property string $nodeDisabledClass
 *
 * @method $this tagNode(string $tag)
 * @method $this tagLevel(string $tag)
 * @method $this nodeHasChildrenClass(string $class)
 * @method $this nodeLevelClass(string $class)
 * @method $this nodeDisabledClass(string $class)
 */
class HtmlConfig extends MainConfig
{
    protected $tagNode = self::TAG_NODE;
    protected $tagLevel = self::TAG_LEVEL;
    protected $nodeHtmlOptions = ['class' => ['node']];
    protected $nodeHasChildrenClass = self::NODE_HAS_CHILDREN_CLASS;
    protected $nodeLevelClass = self::NODE_LEVEL_CLASS;
    protected $nodeDisabledClass = self::NODE_DISABLED_CLASS;

    private const TAG_NODE = 'li';
    private const TAG_LEVEL = 'ul';
    private const NODE_HAS_CHILDREN_CLASS = 'has-children';
    private const NODE_DISABLED_CLASS = 'disabled';
    private const NODE_LEVEL_CLASS = 'node-level';

    public function nodeHtmlOptions(array $options): self
    {
        $this->nodeHtmlOptions = $options;
        return $this;
    }
}

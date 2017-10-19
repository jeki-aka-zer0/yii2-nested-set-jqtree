<?php

namespace jekiakazer0\nsjqtree\src\helpers\config;

/**
 * Class MainConfig
 * @package core\helpers\ns\config
 *
 * @property array $nodes
 * @property KeysConfig $keys
 * @property bool $beginFromFirst
 * @property bool $autoEscape
 * @property array $linkUrl
 * @property string $keyCategory
 * @property array $linkHtmlOptions
 *
 * @method $this beginFromFirst(bool $value)
 * @method $this autoEscape(bool $value)
 * @method $this keyCategory(string $key)
 */
class MainConfig
{
    use ConfigTrait {
        __get as protected traitGet;
    }

    protected $nodes;
    protected $keys;
    protected $beginFromFirst = true;
    protected $autoEscape = false;
    protected $linkUrl = ['update'];
    protected $keyCategory = self::KEY_CATEGORY;
    protected $linkHtmlOptions = ['class' => ['node-link']];

    private const KEY_CATEGORY = 'category';

    public function nodes(array $nodes): self
    {
        $this->nodes = $nodes;
        return $this;
    }

    public function keys(KeysConfig $keysConfig): self
    {
        $this->keys = $keysConfig;
        return $this;
    }

    public function linkUrl(array $url): self
    {
        $this->linkUrl = $url;
        return $this;
    }

    public function linkHtmlOptions(array $options): self
    {
        $this->linkHtmlOptions = $options;
        return $this;
    }

    public function __get($key)
    {
        if ($key === 'keys') {
            return $this->getKeys();
        }

        return $this->traitGet($key);
    }

    private function getKeys()
    {
        if ($this->keys === null) {
            $this->keys = new KeysConfig;
        }

        return $this->keys;
    }
}

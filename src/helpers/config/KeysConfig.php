<?php

namespace jekiakazer0\nsjqtree\src\helpers\config;

/**
 * Class KeysConfig
 * @package core\helpers\ns\config
 *
 * @property string $left
 * @property string $right
 * @property string $depth
 * @property string $label
 * @property string $id
 * @property string $status
 *
 * @method $this left(string $key)
 * @method $this right(string $key)
 * @method $this depth(string $key)
 * @method $this label(string $label)
 * @method $this id(string $id)
 * @method $this status(string $status)
 */
class KeysConfig
{
    use ConfigTrait;

    protected $left = self::LEFT;
    protected $right = self::RIGHT;
    protected $depth = self::DEPTH;
    protected $label = self::LABEL;
    protected $id = self::ID;
    protected $status = self::STATUS;

    private const LEFT = 'lft';
    private const RIGHT = 'rgt';
    private const DEPTH = 'depth';
    private const LABEL = 'label';
    private const ID = 'id';
    private const STATUS = 'status';
}

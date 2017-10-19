<?php

namespace jekiakazer0\nsjqtree\src\assets\exceptions;

use Throwable;

class PropertyNotFoundException extends \Exception
{
    public function __construct(string $key, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Property '{$key}' does not exist.", $code, $previous);
    }
}

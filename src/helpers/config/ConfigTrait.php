<?php

namespace jekiakazer0\nsjqtree\src\helpers\config;

use jekiakazer0\nsjqtree\src\assets\exceptions\PropertyNotFoundException;

trait ConfigTrait
{
    public function __call(string $key, array $arguments)
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $this->getFirstArgument($arguments);
            return $this;
        }

        throw new PropertyNotFoundException($key);
    }

    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        throw new PropertyNotFoundException($key);
    }

    private function getFirstArgument(array $arguments)
    {
        return array_shift($arguments);
    }
}

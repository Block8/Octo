<?php

namespace Octo;

abstract class Model extends \b8\Model implements \ArrayAccess
{
    public function __exists($key)
    {
        return array_key_exists($key, $this->getters);
    }

    public function offsetExists($key)
    {
        return $this->__exists($key);
    }

    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->__set($key, null);
    }
}

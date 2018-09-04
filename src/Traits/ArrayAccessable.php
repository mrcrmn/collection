<?php

namespace mrcrmn\Collection\Traits;

trait ArrayAccessable
{
    /**
     * Gets a value by a given key.
     *
     * @param string|int $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Sets the value by a given key.
     *
     * @param string|int $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->set($value);
        } else {
            $this->set($key, $value);
        }
    }

    /**
     * Checks if the given key exists.
     *
     * @param string|int $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->exists($key);
    }

    /**
     * Unsets the given key.
     *
     * @param string|int $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}
<?php

namespace mrcrmn\Collection\Traits;

trait JsonSerializable
{
    /**
     * Returns the JSON representation of the array.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->json();
    }
}
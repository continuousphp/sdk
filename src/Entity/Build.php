<?php

namespace Continuous\Sdk\Entity;

class Build implements EntityInterface
{
    protected $id;

    protected $attributes = [];

    public function get($property)
    {
        if (true === property_exists($this, $property)) {
            return $this->$property;
        }

        if (isset($this->attributes[$property])) {
            return $this->attributes[$property];
        }

        return null;
    }

    public function id()
    {
        return $this->id;
    }

    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['buildId'];
        $this->attributes = $attributes;
    }
}
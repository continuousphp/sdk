<?php

namespace Continuous\Sdk\Entity;

abstract class EntityAbstract implements EntityInterface
{
    protected $id;
    protected $attributes = [];

    public function has($property)
    {
        return (true === property_exists($this, $property) || isset($this->attributes[$property]));
    }

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
}

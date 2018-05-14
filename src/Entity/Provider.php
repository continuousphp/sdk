<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Provider
 * @package Continuous\Sdk\Entity
 */
class Provider extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['providerId'];
        $this->attributes = $attributes;
    }
}

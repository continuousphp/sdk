<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Package
 * @package Continuous\Sdk\Entity
 */
class Package extends EntityAbstract
{
    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['packageId'];
        $this->attributes = $attributes;
    }
}
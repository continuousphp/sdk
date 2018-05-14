<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Package
 * @package Continuous\Sdk\Entity
 */
class Package extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['packageId'];
        $this->attributes = $attributes;
    }
}

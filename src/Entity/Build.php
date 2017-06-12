<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Build
 * @package Continuous\Sdk\Entity
 */
class Build extends EntityAbstract
{
    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['buildId'];
        $this->attributes = $attributes;
    }
}
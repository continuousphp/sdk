<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Build
 * @package Continuous\Sdk\Entity
 */
class Build extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['buildId'];
        $this->attributes = $attributes;
    }
}

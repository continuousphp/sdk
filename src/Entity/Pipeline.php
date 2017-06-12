<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Pipeline
 * @package Continuous\Sdk\Entity
 */
class Pipeline extends EntityAbstract
{
    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['settingId'];
        $this->attributes = $attributes;
    }
}
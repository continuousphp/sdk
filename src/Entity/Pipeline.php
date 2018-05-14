<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Pipeline
 * @package Continuous\Sdk\Entity
 */
class Pipeline extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['settingId'];
        $this->attributes = $attributes;
    }
}

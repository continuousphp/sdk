<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Repository
 * @package Continuous\Sdk\Entity
 */
class Repository extends EntityAbstract
{
    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['repositoryId'];
        $this->attributes = $attributes;
    }
}
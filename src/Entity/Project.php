<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Project
 * @package Continuous\Sdk\Entity
 */
class Project extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['projectId'];
        $this->attributes = $attributes;
    }
}

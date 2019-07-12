<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Reference
 * @package Continuous\Sdk\Entity
 */
class Reference extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['referenceId'];
        $this->attributes = $attributes;
    }

}

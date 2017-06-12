<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Company
 * @package Continuous\Sdk\Entity
 */
class Company extends EntityAbstract
{
    public function hydrate(Array $attributes)
    {
        $this->id = $attributes['companyId'];
        $this->attributes = $attributes;
    }
}
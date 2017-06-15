<?php

namespace Continuous\Sdk\Entity;

/**
 * Class User
 * @package Continuous\Sdk\Entity
 */
class User extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['userId'];
        $this->attributes = $attributes;
    }

    public function displayName()
    {
        $firstName = $this->get('firstName');
        $lastName = $this->get('lastName');

        return empty($firstName) || empty($lastName) ? $this->get('username') : "{$firstName} {$lastName}";
    }
}

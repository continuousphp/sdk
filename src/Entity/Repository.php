<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Repository
 * @package Continuous\Sdk\Entity
 */
class Repository extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['repositoryId'];
        $this->attributes = $attributes;
    }

    public function getProvider()
    {
        $provider = new Provider();
        $provider->hydrate($this->attributes['_embedded']['provider']);

        return $provider;
    }
}

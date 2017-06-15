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

    public function getProvider()
    {
        $provider = new Provider();
        $provider->hydrate($this->attributes['_embedded']['provider']);

        return $provider;
    }
}

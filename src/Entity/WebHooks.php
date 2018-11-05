<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Webhooks
 * @package Continuous\Sdk\Entity
 */
class WebHooks extends EntityAbstract
{
    public function hydrate(array $attributes)
    {
        $this->id = $attributes['name'];
        $this->attributes = $attributes;
    }

    public function getEvents()
    {
        return $this->attributes['events'];
    }
}

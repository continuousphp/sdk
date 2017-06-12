<?php

namespace Continuous\Sdk\Entity;

interface EntityInterface
{
    public function id();
    public function get($property);
    public function hydrate(Array $attributes);
}
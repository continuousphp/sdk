<?php

namespace Continuous\Sdk\Entity;

interface EntityInterface
{
    public function id();
    public function has($property);
    public function get($property);
    public function hydrate(array $attributes);
}

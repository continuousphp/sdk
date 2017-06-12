<?php

namespace Continuous\Sdk\ResponseLocation;

use Continuous\Sdk\Collection;
use GuzzleHttp\Command\Guzzle\Parameter;
use GuzzleHttp\Command\Guzzle\ResponseLocation\JsonLocation;
use GuzzleHttp\Command\ResultInterface;
use Psr\Http\Message\ResponseInterface;

class HalLocation extends JsonLocation
{
    protected $entityClass;
    protected $type;

    /**
     * Set the name of the location.
     *
     * @param string $locationName
     * @param $entityClass
     */
    public function __construct($locationName, $entityClass)
    {
        parent::__construct($locationName);

        $this->entityClass = $entityClass;
        $this->type = substr($this->locationName, 5);
    }

    public function after(
        ResultInterface $result,
        ResponseInterface$response,
        Parameter $model
    ) {
        $result = parent::after($result, $response, $model);

        if ($result->offsetExists('total_items')) {
            return new Collection($result, $this->type, $this->entityClass);
        }

        $entity = new $this->entityClass;
        $entity->hydrate($result->toArray());

        return $entity;
    }
}
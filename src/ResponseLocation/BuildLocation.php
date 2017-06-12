<?php

namespace Continuous\Sdk\ResponseLocation;

use Continuous\Sdk\Collection;
use Continuous\Sdk\Entity\Build;
use GuzzleHttp\Command\Guzzle\Parameter;
use GuzzleHttp\Command\Guzzle\ResponseLocation\JsonLocation;
use GuzzleHttp\Command\ResultInterface;
use Psr\Http\Message\ResponseInterface;

class BuildLocation extends JsonLocation
{
    /**
     * Set the name of the location
     *
     * @param string $locationName
     */
    public function __construct($locationName = self::class)
    {
        parent::__construct($locationName);
    }

    public function after(
        ResultInterface $result,
        ResponseInterface$response,
        Parameter $model
    ) {
        $result = parent::after($result, $response, $model);

        //TODO handle if result is collection or directly the entity
        return new Collection($result, 'build', Build::class);
    }
}
<?php

namespace Continuous\Fixtures\Sdk;

class GuzzleResult implements \GuzzleHttp\Command\ResultInterface
{
    use \GuzzleHttp\Command\HasDataTrait;

    public function __construct($data)
    {
        $this->data = $data;
    }
}

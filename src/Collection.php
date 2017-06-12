<?php

namespace Continuous\Sdk;

use Continuous\Sdk\Entity\EntityInterface;
use GuzzleHttp\Command\ResultInterface;

class Collection implements ResultInterface
{
    /**
     * @var ResultInterface
     */
    protected $originResult;

    /**
     * @var string
     */
    protected $entityType;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * Collection constructor.
     * @param ResultInterface $originResult
     * @param $entityType
     * @param $entityClass
     */
    public function __construct(ResultInterface $originResult, $entityType, $entityClass)
    {
        $this->originResult = $originResult;
        $this->entityClass = $entityClass;

        if ('y' === substr($entityType, -1)) {
            $this->entityType = substr_replace($entityType, 'ies', -1);
        } else {
            $this->entityType = "{$entityType}s";
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->originResult->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->originResult->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception('Mutation on Collection is not authorized');
    }

    /**
     * @param mixed $offset
     * @throws Exception
     */
    public function offsetUnset($offset)
    {
        throw new Exception('Mutation on Collection is not authorized');
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->originResult['_embedded'][$this->entityType]);
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->originResult['_embedded'][$this->entityType] as $fields) {

            /** @var EntityInterface $entity */
            $entity = new $this->entityClass;
            $entity->hydrate($fields);

            yield $entity->id() => $entity;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'total' => $this->total(),
            'links' => $this->links(),
            'entities' => $this->originResult['_embedded'][$this->entityType],
        ];
    }

    /**
     * @return mixed
     */
    public function total()
    {
        return $this->originResult['total_items'];
    }

    /**
     * @return mixed
     */
    public function links()
    {
        return $this->originResult['_links'];
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }
}
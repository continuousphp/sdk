<?php

namespace Continuous\Tests\Sdk;

use Continuous\Fixtures\Sdk\GuzzleResult;
use Continuous\Sdk\Collection;
use Continuous\Sdk\Entity\Build;
use Continuous\Sdk\Exception;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 4,
            '_embedded' => [
                ''
            ]
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals('myEntities', $collection->getEntityType());
    }

    public function testOffsetExists()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 4,
            '_embedded' => [
                ''
            ]
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');

        $this->assertTrue($collection->offsetExists('total_items'));
        $this->assertFalse($collection->offsetExists('shadowman'));
    }

    public function testOffsetGet()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 3,
            '_embedded' => [
                ''
            ]
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');

        $this->assertNull($collection->offsetGet('shadowman'));
        $this->assertEquals(3, $collection->offsetGet('total_items'));
        $this->assertEquals(4, count($collection->offsetGet('_links')));
    }

    public function testOffsetSet()
    {
        $collection = new Collection(new GuzzleResult([]), 'myEntity', '\Fake\My\Entity');

        $this->setExpectedException(Exception::class, 'Mutation on Collection is not authorized');
        $collection->offsetSet('newattributeinjection', 'value');
    }

    public function testOffsetUnset()
    {
        $collection = new Collection(new GuzzleResult([]), 'myEntity', '\Fake\My\Entity');

        $this->setExpectedException(Exception::class, 'Mutation on Collection is not authorized');
        $collection->offsetUnset('total_items');
    }

    public function testCount()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 10,
            '_embedded' => [
                'myEntities' => [
                    ['name' => 'first'],
                    ['name' => 'second'],
                    ['name' => 'third'],
                ]
            ]
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');

        $this->assertEquals(3, $collection->count());
    }

    public function testGetIterator()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 3,
            '_embedded' => [
                'builds' => [
                    ['buildId' => '8a381341-f4f3-48de-a7bc-8c0f916a058a'],
                    ['buildId' => '07c5e576-4bde-4a6a-b59a-8a9a3bbeec76'],
                    ['buildId' => 'db052939-c455-44af-b1f0-7ca04e9ea2ae'],
                ]
            ]
        ]);

        $buildIds = [
            '8a381341-f4f3-48de-a7bc-8c0f916a058a',
            '07c5e576-4bde-4a6a-b59a-8a9a3bbeec76',
            'db052939-c455-44af-b1f0-7ca04e9ea2ae',
        ];

        $collection = new Collection($result, 'build', Build::class);

        foreach ($collection as $id => $build) {
            $this->assertInstanceOf(Build::class, $build);
            $this->assertTrue(in_array($id, $buildIds));
        }
    }

    public function testToArray()
    {
        $result = new GuzzleResult([
            '_links' => [
                'self' => ['href' => 'https://api.continuousphp.com'],
                'first' => ['href' => 'https://api.continuousphp.com'],
                'last' => ['href' => 'https://api.continuousphp.com'],
                'next' => ['href' => 'https://api.continuousphp.com'],
            ],
            'total_items' => 10,
            '_embedded' => [
                'myEntities' => [
                    ['name' => 'first'],
                    ['name' => 'second'],
                    ['name' => 'third'],
                ]
            ]
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');
        $collectionAsArray = $collection->toArray();

        $this->assertArrayHasKey('entities', $collectionAsArray);
        $this->assertArrayHasKey('total', $collectionAsArray);
        $this->assertArrayHasKey('links', $collectionAsArray);
        $this->assertArrayHasKey('name', $collectionAsArray['entities'][0]);
        $this->assertEquals(10, $collectionAsArray['total']);
    }

    public function testTotal()
    {
        $result = new GuzzleResult([
            'total_items' => 345,
        ]);
        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');
        $this->assertEquals(345, $collection->total());
    }

    public function testLinks()
    {
        $links = [
            'self' => ['href' => 'https://api.continuousphp.com'],
            'first' => ['href' => 'https://api.continuousphp.com'],
            'last' => ['href' => 'https://api.continuousphp.com'],
            'next' => ['href' => 'https://api.continuousphp.com'],
        ];

        $result = new GuzzleResult([
            '_links' => $links,
        ]);

        $collection = new Collection($result, 'myEntity', '\Fake\My\Entity');
        $this->assertEquals($links, $collection->links());
    }

    public function testGetEntityType()
    {
        $collection = new Collection(new GuzzleResult([]), 'myEntity', '\Fake\My\Entity');
        $this->assertEquals('myEntities', $collection->getEntityType());
    }
}

<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

use DanBallance\OasLumen\Doctrine\CriteriaBuilder;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;

class CriteriaBuilderTest extends TestCase
{
    use MockRequestsHelper;

    public function testQuerySingleAttributeEquality()
    {
        $query = [
            'name' => [
                'eq' => 'Jacky'
            ]
        ];
        $criteria = $this->getCriteria('GET', 'crudl.list', 'Player', $query);
        $this->assertEquals(
            [
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testQuerySingleAttributeGreaterThan()
    {
        $query = [
            'age' => [
                'gt' => 40
            ]
        ];
        $criteria = $this->getCriteria('GET', 'crudl.list', 'Player', $query);
        $this->assertEquals(
            [
                ['id' => 17, 'name' => 'Roger', 'age' => 42, 'isOnline' => true],
                ['id' => 18, 'name' => 'Sarah', 'age' => 44, 'isOnline' => false],
                ['id' => 19, 'name' => 'Tom', 'age' => 46, 'isOnline' => true],
                ['id' => 20, 'name' => 'Val', 'age' => 48, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testQuerySingleAttributeGreaterThanOrEqualTo()
    {
        $query = [
            'age' => [
                'gte' => 40
            ]
        ];
        $criteria = $this->getCriteria('GET', 'crudl.list', 'Player', $query);
        $this->assertEquals(
            [
                ['id' => 16, 'name' => 'Paula', 'age' => 40, 'isOnline' => false],
                ['id' => 17, 'name' => 'Roger', 'age' => 42, 'isOnline' => true],
                ['id' => 18, 'name' => 'Sarah', 'age' => 44, 'isOnline' => false],
                ['id' => 19, 'name' => 'Tom', 'age' => 46, 'isOnline' => true],
                ['id' => 20, 'name' => 'Val', 'age' => 48, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testQuerySingleAttributeLessThan()
    {
        $query = [
            'age' => [
                'lt' => 40
            ]
        ];
        $criteria = $this->getCriteria('GET', 'crudl.list', 'Player', $query);
        $this->assertEquals(
            [
                ['id' => 1, 'name' => 'Adam', 'age' => 10, 'isOnline' => true],
                ['id' => 2, 'name' => 'Belinda', 'age' => 12, 'isOnline' => false],
                ['id' => 3, 'name' => 'Carl', 'age' => 14, 'isOnline' => true],
                ['id' => 4, 'name' => 'Dani', 'age' => 16, 'isOnline' => false],
                ['id' => 5, 'name' => 'Ed', 'age' => 18, 'isOnline' => true],
                ['id' => 6, 'name' => 'Fiona', 'age' => 20, 'isOnline' => false],
                ['id' => 7, 'name' => 'Grant', 'age' => 22, 'isOnline' => true],
                ['id' => 8, 'name' => 'Helen', 'age' => 24, 'isOnline' => false],
                ['id' => 9, 'name' => 'Ivor', 'age' => 26, 'isOnline' => true],
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
                ['id' => 11, 'name' => 'Kristian', 'age' => 30, 'isOnline' => true],
                ['id' => 12, 'name' => 'Lisa', 'age' => 32, 'isOnline' => false],
                ['id' => 13, 'name' => 'Mandy', 'age' => 34, 'isOnline' => true],
                ['id' => 14, 'name' => 'Nina', 'age' => 36, 'isOnline' => false],
                ['id' => 15, 'name' => 'Olly', 'age' => 38, 'isOnline' => true],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testQuerySingleAttributeLessThanOrEqualTo()
    {
        $query = [
            'age' => [
                'lte' => 40
            ]
        ];
        $criteria = $this->getCriteria('GET', 'crudl.list', 'Player', $query);
        $this->assertEquals(
            [
                ['id' => 1, 'name' => 'Adam', 'age' => 10, 'isOnline' => true],
                ['id' => 2, 'name' => 'Belinda', 'age' => 12, 'isOnline' => false],
                ['id' => 3, 'name' => 'Carl', 'age' => 14, 'isOnline' => true],
                ['id' => 4, 'name' => 'Dani', 'age' => 16, 'isOnline' => false],
                ['id' => 5, 'name' => 'Ed', 'age' => 18, 'isOnline' => true],
                ['id' => 6, 'name' => 'Fiona', 'age' => 20, 'isOnline' => false],
                ['id' => 7, 'name' => 'Grant', 'age' => 22, 'isOnline' => true],
                ['id' => 8, 'name' => 'Helen', 'age' => 24, 'isOnline' => false],
                ['id' => 9, 'name' => 'Ivor', 'age' => 26, 'isOnline' => true],
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
                ['id' => 11, 'name' => 'Kristian', 'age' => 30, 'isOnline' => true],
                ['id' => 12, 'name' => 'Lisa', 'age' => 32, 'isOnline' => false],
                ['id' => 13, 'name' => 'Mandy', 'age' => 34, 'isOnline' => true],
                ['id' => 14, 'name' => 'Nina', 'age' => 36, 'isOnline' => false],
                ['id' => 15, 'name' => 'Olly', 'age' => 38, 'isOnline' => true],
                ['id' => 16, 'name' => 'Paula', 'age' => 40, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testPathSingleAttributeEquality()
    {
        $criteria = $this->getCriteria(
            'GET', 
            'crudl.read', 
            'Player',
            [],
            [10]
        );
        $this->assertEquals(
            [
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    public function testRequestBodySingleAttributeEquality()
    {
        $criteria = $this->getCriteria(
            'PUT', 
            'crudl.update', 
            'Player',
            [],
            [],
            [
                'id' => 10,
                'name' =>
                'Jacky',
                'age' => 28,
                'isOnline' => false
            ]
        );
        $this->assertEquals(
            [
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
            ],
            array_values($this->getCollection()->matching($criteria)->toArray())
        );
    }

    /**
     * Ultimately we want to query doctrine's ORM and ODM persistence APIs 
     * but since ArrayCollection offers the same interface 
     * we'll use it instead to keep tests fast and simple in memory.
     */
    protected function getCollection()
    {
        return new ArrayCollection(
            [
                ['id' => 1, 'name' => 'Adam', 'age' => 10, 'isOnline' => true],
                ['id' => 2, 'name' => 'Belinda', 'age' => 12, 'isOnline' => false],
                ['id' => 3, 'name' => 'Carl', 'age' => 14, 'isOnline' => true],
                ['id' => 4, 'name' => 'Dani', 'age' => 16, 'isOnline' => false],
                ['id' => 5, 'name' => 'Ed', 'age' => 18, 'isOnline' => true],
                ['id' => 6, 'name' => 'Fiona', 'age' => 20, 'isOnline' => false],
                ['id' => 7, 'name' => 'Grant', 'age' => 22, 'isOnline' => true],
                ['id' => 8, 'name' => 'Helen', 'age' => 24, 'isOnline' => false],
                ['id' => 9, 'name' => 'Ivor', 'age' => 26, 'isOnline' => true],
                ['id' => 10, 'name' => 'Jacky', 'age' => 28, 'isOnline' => false],
                ['id' => 11, 'name' => 'Kristian', 'age' => 30, 'isOnline' => true],
                ['id' => 12, 'name' => 'Lisa', 'age' => 32, 'isOnline' => false],
                ['id' => 13, 'name' => 'Mandy', 'age' => 34, 'isOnline' => true],
                ['id' => 14, 'name' => 'Nina', 'age' => 36, 'isOnline' => false],
                ['id' => 15, 'name' => 'Olly', 'age' => 38, 'isOnline' => true],
                ['id' => 16, 'name' => 'Paula', 'age' => 40, 'isOnline' => false],
                ['id' => 17, 'name' => 'Roger', 'age' => 42, 'isOnline' => true],
                ['id' => 18, 'name' => 'Sarah', 'age' => 44, 'isOnline' => false],
                ['id' => 19, 'name' => 'Tom', 'age' => 46, 'isOnline' => true],
                ['id' => 20, 'name' => 'Val', 'age' => 48, 'isOnline' => false],
            ]
        );
    }

    protected function getCriteria(
        string $method,
        string $operationId,
        string $schemaName,
        array $query,
        array $routeParams = [],
        array $requestBody = []
    ) {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);

        $request = $this->mockRequest(
            $method,
            'http://localhost',
            $query,
            $requestBody
        );
        return (new CriteriaBuilder($spec))->make(
            $request,
            $routeParams,
            $spec->getOperation($operationId),
            $spec->getSchema($schemaName)
        );
    }
}

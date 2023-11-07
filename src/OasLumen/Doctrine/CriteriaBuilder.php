<?php

namespace DanBallance\OasLumen\Doctrine;

use Zend\Diactoros\ServerRequest;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Specification;

class CriteriaBuilder
{
    protected $spec;
    protected $operation;

    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    /**
     * Take a Zend\Diactoros\ServerRequest and a DanBallance\OasTools\Specification\Fragment for Operation
     * and Schema - and returns a Doctrine\Common\Collections\Criteria object.
     * 
     * Eventually we want to support all of these comparisons:
     * https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expressions.html#expressions
     * 
     * @TODO it would be better to simply look at the Operation's parameters and then look for those 
     * parameters in the path, query and request bodies.
     */
    public function make(
        ServerRequest $request,
        array $routeParams,
        FragmentInterface $operation,
        FragmentInterface $schema
    ) : Criteria {
        $this->operation = $operation;
        $criteria = new Criteria();
        $criteria = $this->handlePath(
            $criteria,
            $operation,
            $routeParams
        );
        $criteria = $this->handleQueryString(
            $criteria,
            $request->getUri()->getQuery()
        );
        $criteria = $this->handleRequestBody(
            $criteria,
            $request->getBody()->__toString(),
            $schema,
            $operation
        );
        return $criteria;
    }

    protected function handlePath(
        Criteria $criteria,
        FragmentInterface $operation,
        array $routeParams
    ) : Criteria {
        if ($routeParams && isset($operation->toArray()['path'])) {
            $path = $operation->toArray()['path'];
            $routeParamsAssoc = $this->spec->lookupRouteParams($path, $routeParams);
            foreach ($routeParamsAssoc as $attr => $value) {
                $expr = new Comparison($attr, Comparison::EQ, $value);
                $criteria->andWhere($expr);
            }
        }
        return $criteria;
    }

    protected function handleQueryString(
        Criteria $criteria,
        string $queryString
    ) : Criteria {
        if (!$queryString) {
            return $criteria;
        }
        parse_str($queryString, $query);
        foreach ($query as $attr => $filter) {
            if ($this->isFilterParam($attr, $this->operation->toArray())) {
                foreach ($filter as $op => $value) {
                    if ($op == 'eq') {
                        $expr = new Comparison($attr, Comparison::EQ, $value);
                        $criteria->andWhere($expr);
                    } elseif ($op == 'gt') {
                        $expr = new Comparison($attr, Comparison::GT, $value);
                        $criteria->andWhere($expr);
                    } elseif ($op == 'gte') {
                        $expr = new Comparison($attr, Comparison::GTE, $value);
                        $criteria->andWhere($expr);
                    } elseif ($op == 'lt') {
                        $expr = new Comparison($attr, Comparison::LT, $value);
                        $criteria->andWhere($expr);
                    } elseif ($op == 'lte') {
                        $expr = new Comparison($attr, Comparison::LTE, $value);
                        $criteria->andWhere($expr);
                    }
                }
            }
        }
        return $criteria;
    }

    protected function handleRequestBody(
        Criteria $criteria,
        string $requestBody,
        FragmentInterface $schema,
        FragmentInterface $operation
    ) : Criteria {
        if (!$requestBody) {
            return $criteria;
        }
        // @TODO we mustn't assume JSON here...
        $requestBody = json_decode($requestBody, true);
        foreach ($schema->toArray()['properties'] as $attr => $schema) {
            $isPrimary = $schema['x-primary-key'] ?? false;
            $value = $requestBody[$attr] ?? null;
            if ($isPrimary && $value) {
                $expr = new Comparison($attr, Comparison::EQ, $value);
                $criteria->andWhere($expr);
            }
        }
        return $criteria;
    }

    protected function isFilterParam(string $attr, array $operation)
    {
        if (!isset($operation['parameters'])) {
            return false;
        }
        foreach ($operation['parameters'] as $param) {
            if (isset($param['name']) 
                && $param['name'] == $attr
                && isset($param['x-filter'])
                && $param['x-filter'] == 'attribute'
            ) {
                return true;
            }
        }
        return false;
    }
}

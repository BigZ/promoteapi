<?php

namespace bigz\halapi\Factory;

use bigz\halapi\Relation\RelationInterface;

class RelationFactory
{
    /**
     * @var array
     */
    private $relations;

    /**
     * RelationFactory constructor.
     * @param array $relations
     */
    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    /**
     * Get the relations of an Entity.
     * Relations processors are passed to the constructor.
     * @param $resource
     * @return array
     */
    public function getRelations($resource)
    {
        $resourceRelations = [];

        foreach ($this->relations as $relation) {
            if ($relation instanceof RelationInterface) {
                $relationContent = $relation->getRelation($resource);

                if ($relationContent) {
                    $resourceRelations[$relation->getName()] = $relation->getRelation($resource);

                }
            }
        }

        return $resourceRelations;
    }
}

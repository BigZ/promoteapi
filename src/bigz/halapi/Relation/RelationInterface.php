<?php

namespace bigz\halapi\Relation;

interface RelationInterface
{
    public function getName();

    public function getRelation($resource);
}
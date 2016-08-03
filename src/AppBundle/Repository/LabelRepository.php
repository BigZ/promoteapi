<?php

namespace AppBundle\Repository;
use bigz\halapi\Representation\RestEntityRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class LabelRepository extends EntityRepository
{
    use RestEntityRepositoryTrait;
}

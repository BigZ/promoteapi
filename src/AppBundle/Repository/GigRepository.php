<?php

namespace AppBundle\Repository;

use bigz\halapi\Representation\RestEntityRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class GigRepository  extends EntityRepository
{
    use RestEntityRepositoryTrait;
}

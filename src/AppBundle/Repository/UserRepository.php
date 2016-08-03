<?php

namespace AppBundle\Repository;

use bigz\halapi\Representation\RestEntityRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class UserRepository  extends EntityRepository
{
    use RestEntityRepositoryTrait;
}

<?php

namespace AppBundle\Repository;

use bigz\halapi\Representation\RestEntityRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class ArtistRepository extends EntityRepository
{
    use RestEntityRepositoryTrait;
}

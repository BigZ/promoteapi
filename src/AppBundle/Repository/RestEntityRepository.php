<?php
/**
 * Created by PhpStorm.
 * User: developpeur
 * Date: 27/04/2016
 * Time: 11:21
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RestEntityRepository extends EntityRepository
{
    public function findAllSorted(array $sorting, array $filterValues, array $filerOperators)
    {
        $fields = array_keys($this->getClassMetadata()->fieldMappings);
        $queryBuilder = $this->createQueryBuilder('e');

        foreach ($fields as $field) {
            if (isset($sorting[$field])) {
                $direction = ($sorting[$field] === 'asc') ? 'asc' : 'desc';
                $queryBuilder->addOrderBy('e.'.$field, $direction);
            }

            if (isset($filterValues[$field])) {
                $operator = '=';

                if (isset($filerOperators[$field]) && in_array($filerOperators[$field], ['>','<','>=', '<=', '='])) {
                    $operator = $filerOperators[$field];
                }
                
                $queryBuilder->andWhere('e.'.$field.$operator.$filterValues[$field]);
            }
        }

        return $queryBuilder;
    }
}
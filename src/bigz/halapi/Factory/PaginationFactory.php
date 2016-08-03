<?php
namespace bigz\halapi\Factory;

use bigz\halapi\Representation\PaginatedRepresentation;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    public $entityManager;

    public $router;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function getRepresentation($className, $paramFetcher)
    {
        $repository = $this->entityManager->getRepository($className);
        list($page, $limit, $sorting, $filterValues, $filerOperators) = $this->addPaginationParams($paramFetcher);

        $this->paramFetcher = $paramFetcher;
        $queryBuilder = $repository->findAllSorted($sorting, $filterValues, $filerOperators);
        $shortName = (new \ReflectionClass($className))->getShortName();

        $pagerAdapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pagerAdapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return new PaginatedRepresentation(
            $paramFetcher->get('page'),
            $paramFetcher->get('limit'),
            [
                'self' => $this->getPaginatedRoute($shortName, $limit, $page, $sorting),
                'first' => $this->getPaginatedRoute($shortName, $limit, 1, $sorting),
                'next' => $this->getPaginatedRoute(
                    $shortName,
                    $limit, $page < $pager->getNbPages() ? $page + 1 : $pager->getNbPages(),
                    $sorting
                ),
                'last' => $this->getPaginatedRoute($shortName, $limit, $pager->getNbPages(), $sorting)
            ],
            (array)$pager->getCurrentPageResults()
        );
    }

    private function addPaginationParams(ParamFetcher $paramFetcher)
    {
        $limitParam = new QueryParam();
        $limitParam->name = "limit";
        $limitParam->requirements = "\d+";
        $limitParam->default = "20";
        $paramFetcher->addParam($limitParam);

        $pageParam = new QueryParam();
        $pageParam->name = "page";
        $pageParam->requirements = "\d+";
        $pageParam->default = "1";
        $paramFetcher->addParam($pageParam);

        $sortingParam = new QueryParam();
        $sortingParam->name = "sorting";
        $sortingParam->array = true;
        $paramFetcher->addParam($sortingParam);

        $filterValueParam = new QueryParam();
        $filterValueParam->name = "filtervalue";
        $filterValueParam->array = true;
        $paramFetcher->addParam($filterValueParam);

        $filterOperatorParam = new QueryParam();
        $filterOperatorParam->name = "filteroperator";
        $filterOperatorParam->array = true;
        $paramFetcher->addParam($filterOperatorParam);

        return [
            $paramFetcher->get('page'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('sorting'),
            $paramFetcher->get('filtervalue'),
            $paramFetcher->get('filteroperator'),

        ];
    }

    private function getPaginatedRoute($name, $limit, $page, $sorting)
    {
        return $this->router->generate(
            'get_'.strtolower($name).'s', [
                'sorting' => $sorting,
                'page' => $page,
                'limit' => $limit
            ]
        );
    }
}
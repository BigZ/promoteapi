<?php

namespace AppBundle\Controller;

use AppBundle\Annotation\Embeddable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Mapping\ManyToMany;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Route;
use JMS\Serializer\Annotation\Expose;

class HALController extends FOSRestController
{
    public $paramFetcher;

    public function getPaginatedRepresentation($name, ParamFetcher $paramFetcher)
    {
        list($page, $limit, $sorting) = $this->addPaginationParams($paramFetcher);
        $this->paramFetcher = $paramFetcher;
        $queryBuilder = $this->getRepository()->findAllSorted($paramFetcher->get('sorting'));

        $pagerAdapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pagerAdapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return [
            'page' => $paramFetcher->get('page'),
            'limit' => $paramFetcher->get('limit'),
            '_links' => [
                'self' => $this->getPaginatedRoute($name, $limit, $page, $sorting),
                'first' => $this->getPaginatedRoute($name, $limit, 1, $sorting),
                'next' => $this->getPaginatedRoute(
                    $name,
                    $limit, $page < $pager->getNbPages() ? $page + 1 : $pager->getNbPages(),
                    $sorting
                ),
                'last' => $this->getPaginatedRoute($name, $limit, $pager->getNbPages(), $sorting)
            ],
            '_embedded' => $this->processCollection(iterator_to_array($pager->getCurrentPageResults()))
        ];
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

        return [$paramFetcher->get('page'), $paramFetcher->get('limit'), $paramFetcher->get('sorting')];
    }

    private function addEmbedParams(ParamFetcher $paramFetcher)
    {
        $sortingParam = new QueryParam();
        $sortingParam->name = "embed";
        $sortingParam->array = true;
        $paramFetcher->addParam($sortingParam);

        return $paramFetcher->get('embed');
    }

    private function processCollection(array $collection)
    {
        $resources = [];
        foreach ($collection as $resource) {
            $resources[] = $this->getResourceRepresentation($resource);
        }

        return $resources;
    }

    public function getResourceRepresentation($resource)
    {
        $representation = [];
        $reflectionClass = new \ReflectionClass($resource);

        foreach ($reflectionClass->getProperties() as $field) {
            $reflectionProp = $reflectionClass->getProperty($field->name);
            foreach ($this->get('annotation_reader')->getPropertyAnnotations($reflectionProp) as $annotation) {
                if ($annotation instanceof Expose) {
                    $representation[$field->name] = $resource->{'get'.ucfirst($field->name)}();
                }
            }
        }
        
        $representation = array_merge($representation, $this->getResourceLinks($resource));

        return $representation;
    }

    private function getResourceLinks($resource)
    {
        $reflectionClass = new \ReflectionClass($resource);
        $embedded = [];
        $links =  [
            'self' => $this->get('router')->generate(
                'get_'.strtolower($reflectionClass->getShortName()),
                [strtolower($reflectionClass->getShortName()) => $resource->getId()]
            )
        ];

        $annotationReader = $this->get('annotation_reader');

        foreach ($reflectionClass->getProperties() as $property) {
            $embeddable = $annotationReader->getPropertyAnnotation($property, Embeddable::class);

            if (null !== $embeddable) {
                $propertyName = $property->getName();
                $relationContent = $resource->{'get'.ucfirst($propertyName)}();
                $links[$propertyName] = $this->getRelationLinks($property, $relationContent);
                $embeds = $this->addEmbedParams($this->paramFetcher);
                if (in_array($propertyName, $embeds)) {
                    $embedded[$propertyName] = $relationContent;
                }
            }
        }

        return ['_links' => $links, '_embedded' => $embedded ?: null];
    }

    private function getRelationLinks($property, $relationContent)
    {
        if ($relationContent instanceof Collection) {
            $links = [];

            foreach ($relationContent as $relation) {
                $links[] = $this->getRelationLink($property, $relation);
            }

            return $links;
        }

        return $this->getRelationLink($property, $relationContent);;
    }

    protected function getRelationLink($property, $relationContent)
    {
        $entjtyManager = $this->getDoctrine()->getManager();
        $annotationReader = $this->get('annotation_reader');
        $meta = $entjtyManager->getClassMetadata(get_class($relationContent));
        $identifier = $meta->getSingleIdentifierFieldName();

        foreach ($annotationReader->getPropertyAnnotations($property) as $annotation) {
            if (isset($annotation->targetEntity)) {
                try {
                    $id = $entjtyManager->getUnitOfWork()->getEntityIdentifier($relationContent)[$identifier];

                    return $this->get('router')->generate(
                        'get_'.strtolower($annotation->targetEntity),
                        [strtolower($annotation->targetEntity) => $id]
                    );
                } catch (\Exception $exception) {
                    return null;
                }
            }
        }

        return null;
    }

    private function getPaginatedRoute($name, $limit, $page, $sorting)
    {
        return $this->get('router')->generate('get_'.$name.'s', [
            'sorting' => $sorting,
            'page' => $page,
            'limit' => $limit
        ]);
    }
}
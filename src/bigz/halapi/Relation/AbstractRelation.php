<?php

namespace bigz\halapi\Relation;

use bigz\halapi\Annotation\Embeddable;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class AbstractRelation
{
    protected $router;

    protected $annotationReader;

    protected $entityManager;

    protected $requestStack;

    public function __construct(
        RouterInterface $router,
        Reader $annotationReader,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    protected function isEmbeddable($property)
    {
        return null !== $this->annotationReader->getPropertyAnnotation($property, Embeddable::class);
    }
}

<?php

namespace bigz\halapi\Factory;


use bigz\halapi\Annotation\Embeddable;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class AbstractRelationFactory
{
    protected $router;

    protected $annotationReader;

    protected $entityManager;

    public function __construct(
        RouterInterface $router,
        Reader $annotationReader,
        EntityManagerInterface $entityManager
    ) {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->entityManager = $entityManager;
    }

    protected function isEmbeddable($property)
    {
        return null !== $this->annotationReader->getPropertyAnnotation($property, Embeddable::class);
    }
}
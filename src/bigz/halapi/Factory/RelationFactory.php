<?php

namespace bigz\halapi\Factory;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RelationFactory
{
    /**
     * @var LinksFactory
     */
    private $linksFactory;

    /**
     * @var EmbeddedFactory
     */
    private $embeddedFactory;

    public function __construct(
        RouterInterface $router,
        Reader $annotationReader,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->linksFactory = new LinksFactory($router, $annotationReader, $entityManager, $requestStack);
        $this->embeddedFactory = new EmbeddedFactory($router, $annotationReader, $entityManager, $requestStack);
    }

    public function getLinks($resource)
    {
        return $this->linksFactory->getLinks($resource);
    }

    public function getEmbedded($resource)
    {
        return $this->embeddedFactory->getEmbedded($resource);
    }
}

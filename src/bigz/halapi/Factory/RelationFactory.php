<?php

namespace bigz\halapi\Factory;

use bigz\halapi\Annotation\Embeddable;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
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
        EntityManagerInterface $entityManager
    ) {
        $this->linksFactory = new LinksFactory($router, $annotationReader, $entityManager);
        $this->embeddedFactory = new EmbeddedFactory($router, $annotationReader, $entityManager);
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
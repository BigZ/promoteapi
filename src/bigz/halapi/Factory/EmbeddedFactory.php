<?php

namespace bigz\halapi\Factory;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class EmbeddedFactory extends AbstractRelationFactory
{
    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    public function __construct(
        RouterInterface $router,
        Reader $annotationReader,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        parent::__construct($router, $annotationReader, $entityManager, $requestStack);
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getEmbedded($resource)
    {
        $reflectionClass = new \ReflectionClass($resource);
        $embedded = [];
        $requestedEmbedded = $this->getEmbeddedParams();

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();

            if ($this->isEmbeddable($property) && $this->isEmbeddedRequested($propertyName, $requestedEmbedded)) {
                $embedded[$property->getName()] = $this->getEmbeddedContent($resource, $property);
            }
        }

        return $embedded;
    }

    private function isEmbeddedRequested($propertyName, $requestedEmbedded)
    {
        return in_array($propertyName, $requestedEmbedded);
    }

    private function getEmbeddedContent($resource, $property)
    {
        $value = $resource->{'get'.ucfirst($property->getName())}();

        return $this->serializer->toArray($value);
    }

    private function getEmbeddedParams()
    {
        $request = $this->requestStack->getMasterRequest();

        $embed = $request->query->get('embed');

        if (!is_array($embed)) {
            return [];
        }

        return $embed;
    }
}

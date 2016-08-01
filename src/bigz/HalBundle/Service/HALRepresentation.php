<?php

namespace bigz\HalBundle\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Serializer;
use Symfony\Component\Routing\Router;
use FOS\RestBundle\Request\ParamFetcher;

class HALRepresentation
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CachedReader
     */
    private $annotationReader;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ParamFetcher
     */
    public $paramFetcher;

    public function __construct(
        Serializer $serializer,
        EntityManager $entityManager,
        CachedReader $annotationReader,
        Router $router
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->annotationReader = $annotationReader;
        $this->router = $router;
    }

    public function processCollection(array $collection)
    {
        $resources = [];
        foreach ($collection as $resource) {
            $resources[] = $this->getResourceRepresentation($resource);
        }

        return $resources;
    }

    public function getResourceRepresentation($resource)
    {
        $resourceArray = json_decode($this->serializer->serialize($resource, 'json'), 1);
        //$resourceArray = $this->processResource($resource, $resourceArray);

        return $this->getResourceLinks($resource);
        return array_merge(
            $resourceArray,
            $this->getResourceLinks($resource)
        );
    }

    private function getResourceLinks($resource)
    {
        $reflectionClass = new \ReflectionClass($resource);
        $embedded = [];
        $links =  [
            'self' => $this->router->generate(
                'get_'.strtolower($reflectionClass->getShortName()),
                [strtolower($reflectionClass->getShortName()) => $resource->getId()]
            )
        ];

        foreach ($reflectionClass->getProperties() as $property) {
            $embeddable = $this->annotationReader->getPropertyAnnotation($property, Embeddable::class);

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
        $annotationReader = $this->get('annotation_reader');
        $meta = $this->entityManager->getClassMetadata(get_class($relationContent));
        $identifier = $meta->getSingleIdentifierFieldName();

        foreach ($annotationReader->getPropertyAnnotations($property) as $annotation) {
            if (isset($annotation->targetEntity)) {
                try {
                    $id = $this->entityManager->getUnitOfWork()->getEntityIdentifier($relationContent)[$identifier];

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
}
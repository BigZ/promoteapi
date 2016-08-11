<?php

/**
 * Created by PhpStorm.
 * User: developpeur
 * Date: 23/07/2016
 * Time: 12:52.
 */
namespace bigz\halapi\Factory;

use Doctrine\Common\Collections\Collection;

class LinksFactory extends AbstractRelationFactory
{
    public function getLinks($resource)
    {
        $reflectionClass = new \ReflectionClass($resource);
        $links = $this->getSelfLink($resource, $reflectionClass);

        foreach ($reflectionClass->getProperties() as $property) {
            if ($this->isEmbeddable($property) && $property->getName()) {
                $propertyName = $property->getName();
                $relationContent = $resource->{'get'.ucfirst($propertyName)}();
                $links[$propertyName] = $this->getRelationLinks($property, $relationContent);

                if (!$links[$propertyName]) {
                    unset($links[$propertyName]);
                }
            }
        }

        return $links;
    }

    private function getSelfLink($resource, $reflectionClass)
    {
        if ($resource instanceof \Traversable) {
            return;
        }

        try {
            return [
                'self' => $this->router->generate(
                    'get_'.strtolower($reflectionClass->getShortName()),
                    [strtolower($reflectionClass->getShortName()) => $resource->getId()]
                ),
            ];
        } catch (\Exception $exception) {
            return [];
        }
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

        return $this->getRelationLink($property, $relationContent);
    }

    protected function getRelationLink($property, $relationContent)
    {
        $meta = $this->entityManager->getClassMetadata(get_class($relationContent));
        $identifier = $meta->getSingleIdentifierFieldName();

        foreach ($this->annotationReader->getPropertyAnnotations($property) as $annotation) {
            if (isset($annotation->targetEntity)) {
                try {
                    $id = $this->entityManager->getUnitOfWork()->getEntityIdentifier($relationContent)[$identifier];

                    return $this->router->generate(
                        'get_'.strtolower($annotation->targetEntity),
                        [strtolower($annotation->targetEntity) => $id]
                    );
                } catch (\Exception $exception) {
                    return;
                }
            }
        }

        return;
    }
}

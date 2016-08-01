<?php
/**
 * Created by PhpStorm.
 * User: developpeur
 * Date: 23/07/2016
 * Time: 12:52
 */

namespace bigz\halapi\Factory;


class LinksFactory
{
    public function getLinks($resource)
    {
        $reflectionClass = new \ReflectionClass($resource);
        $links = $this->getSelfLink($resource, $reflectionClass);

        foreach ($reflectionClass->getProperties() as $property) {
            if ($this->isEmbbedable($property) && $property->getName()) {
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
}
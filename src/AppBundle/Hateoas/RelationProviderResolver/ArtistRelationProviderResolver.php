<?php

namespace AppBundle\Hateoas\RelationProviderResolver;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Label;
use Hateoas\Configuration\Provider\Resolver\RelationProviderResolverInterface;
use Hateoas\Configuration\RelationProvider as RelationProviderConfiguration;
use Hateoas\Configuration\Metadata\ClassMetadataInterface;
use Hateoas\Configuration as Hateoas;

class ArtistRelationProviderResolver implements RelationProviderResolverInterface
{
    /**
     * @inheritdoc
     */
    public function getRelationProvider(RelationProviderConfiguration $configuration, $object)
    {
        if (is_callable('self::getRelationsFor'.ucfirst($configuration->getName()))) {
            return [$this, 'getRelationsFor'.ucfirst($configuration->getName())];
        }

        return function () {
            return null; 
        };
    }

    public function getRelationsForArtistLabels(Artist $artist)
    {
        $labelRoutes = [];

        foreach ($artist->getLabels() as $label) {
            $labelRoutes[] = new Hateoas\Relation(
                'labels',
                new Hateoas\Route('label_view', ['id' => $label->getid()])
            );
        }

        return $labelRoutes;
    }

    public function getRelationsForLabelArtists(Label $label)
    {
        $artistRoutes = [];

        foreach ($label->getArtists() as $artist) {
            $artistRoutes[] = new Hateoas\Relation(
                'artists',
                new Hateoas\Route('artist_view', ['id' => $artist->getid()])
            );
        }

        return $artistRoutes;
    }
}
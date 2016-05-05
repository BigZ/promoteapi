<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use AppBundle\Form\Type\ArtistType;
use FOS\RestBundle\Request\ParamFetcher;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\RouteAwareRepresentation;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hateoas\Configuration\Annotation as Hateoas;

class ArtistController extends HALController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getArtistsAction(ParamFetcher $paramFetcher)
    {
        return $this->getPaginatedRepresentation('artist', $paramFetcher);
    }

    /**
     * @param Artist $artist
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getArtistAction(Artist $artist, ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
        return $this->getResourceRepresentation($artist);
    }

    /**
     * @param Request $request
     *
     * @Security("is_granted('CREATE')")
     *
     * @return mixed
     */
    public function postArtistAction(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $artist->setCreatedBy($this->getUser());
            $manager->persist($artist);
            $manager->flush();

            return ['status' => 'created', 'resource_id' => $artist->getId()];
        }

        return $form;
    }

    /**
     * @param Request $request
     *
     * @Security("is_granted('EDIT')")
     *
     * @return mixed
     */
    public function putArtistAction(Request $request, Artist $artist)
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($artist);
            $manager->flush();

            return ['status' => 'updated', 'resource_id' => $artist->getId()];
        }

        return $form;
    }

    /**
     * @Route(name="artist_delete")
     * @param Artist $artist
     * @return array
     */
    public function deleteArtistAction(Artist $artist)
    {
        $id = $artist->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($artist);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $id];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Artist');
    }
}

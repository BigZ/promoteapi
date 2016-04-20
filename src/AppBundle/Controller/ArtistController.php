<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Form\Type\ArtistType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ArtistController extends FOSRestController
{
    /**
     * @return array
     */
    public function getArtistsAction()
    {
        return [
            'artists' => $this->getRepository()->findAll()
        ];
    }

    /**
     * @param Artist $artist
     * @return array
     */
    public function getArtistAction(Artist $artist)
    {
        return ['artist' => $artist];
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

    private function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Artist');
    }
}

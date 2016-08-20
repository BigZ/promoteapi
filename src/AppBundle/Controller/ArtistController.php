<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use AppBundle\Form\Type\ArtistType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use bigz\halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

class ArtistController extends FOSRestController
{
    /**
     * Get artists.
     *
     * @ApiDoc(
     *     resource=true,
     *     filters=PaginatedRepresentation::FILTERS,
     *     output="bigz\halapi\Representation\PaginatedRepresentation",
     *     statusCodes = {
     *         200 = "Returns the paginated artists collection",
     *         400 = "Error"
     *     }
     *     )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getArtistsAction(ParamFetcher $paramFetcher)
    {
        return $this->get('bigz_halapi.pagination_factory')->getRepresentation(Artist::class, $paramFetcher);
    }

    /**
     * Get an artist.
     *
     * @Apidoc(
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Artist not found"
     *   },
     *  output="AppBundle\Entity\Artist",
     * )
     *
     * @param Artist $artist
     *
     * @return array
     */
    public function getArtistAction(Artist $artist)
    {
        return $artist;
    }

    /**
     * Create a new Artist.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\ArtistType",
     *  output="AppBundle\Entity\Artist",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('create')")
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

            return $artist;
        }

        return $form;
    }

    /**
     * Update an Artist.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\ArtistType",
     *  output="AppBundle\Entity\Artist"
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('edit')")
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

            return $artist;
        }

        return $form;
    }

    /**
     * Delete an Artist.
     *
     * @ApiDoc(statusCodes = {
     *     204 = "Artist deleted",
     *     404 = "Artist not found"
     *   }
     * )
     * @Security("is_granted('delete')")
     *
     * @param Artist $artist
     *
     * @return array
     */
    public function deleteArtistAction(Artist $artist)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($artist);
        $manager->flush();

        // Dirty Fix for php webserver
        // see https://github.com/symfony/symfony/issues/12744
        header_register_callback(function() {
            header_remove('Content-type');
            header('Content-Type: application/json');
        });

        return new Response('{}', 204);
    }

    /**
     * Upload a new artist picture
     *
     * @param Request $request
     * @param Artist $artist
     * @return array
     */
    public function putArtistPictureAction(Request $request, Artist $artist)
    {
        $tmpFile = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];

        file_put_contents($tmpFilePath, $request->getContent());
        $file = new UploadedFile($tmpFilePath, $tmpFilePath);

        $artist->setImageFile($file);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($artist);
        $manager->flush();

        return $artist->getId();
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Artist');
    }
}

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

            return $artist;
        }

        return $form;
    }

    /**
     * Delete an Artist.
     *
     * @ApiDoc(
     *  input="AppBundle\Artist"
     * )
     * @Security("is_granted('DELETE')")
     *
     * @param Artist $artist
     *
     * @return array
     */
    public function deleteArtistAction(Artist $artist)
    {
        $resourceId = $artist->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($artist);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $resourceId];
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

        return ['status' => 'updated', 'resource_id' => $artist->getId()];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Artist');
    }
}

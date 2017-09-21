<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use AppBundle\Form\Type\ArtistType;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArtistController
 * @author Romain Richard
 */
class ArtistController extends Controller
{
    use ControllerTrait;

    /**
     * Get artists.
     *
     * @ApiDoc(
     *     resource=true,
     *     filters=PaginatedRepresentation::FILTERS,
     *     output="Halapi\Representation\PaginatedRepresentation",
     *     statusCodes = {
     *         200 = "Returns the paginated artist collection",
     *         400 = "Error"
     *       }
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
     *     200 = "Returns an artist",
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
     *     200 = "Artist created",
     *     400 = "Invalid request",
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

        return $this->view($form, 400);
    }

    /**
     * Update an Artist.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\ArtistType",
     *  output="AppBundle\Entity\Artist",
     *  statusCodes = {
     *     200 = "Artist patched",
     *     400 = "Invalid request",
     *     404 = "Artist not found"
     *   }
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
        $form = $this->createForm(ArtistType::class, $artist, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($artist);
            $manager->flush();

            return $artist;
        }

        return $this->view($form, 400);
    }

    /**
     * Pacth an Artist.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\ArtistType",
     *  output="AppBundle\Entity\Artist",
     *  statusCodes = {
     *     200 = "Artist updated",
     *     400 = "Invalid request",
     *     404 = "Artist not found"
     *   }
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('edit')")
     *
     * @return mixed
     */
    public function patchArtistAction(Request $request, Artist $artist)
    {
        $form = $this->createForm(ArtistType::class, $artist, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($artist);
            $manager->flush();

            return $artist;
        }

        return $this->view($form, 400);
    }

    /**
     * Delete an Artist.
     *
     * @ApiDoc(statusCodes               = {
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
        header_register_callback(
            function () {
                header_remove('Content-type');
                header('Content-Type: application/json');
            }
        );

        return new Response('{}', 204);
    }

    /**
     * Upload a new artist picture.
     *
     * @param Request $request
     * @param Artist  $artist
     *
     * @return array
     */
    public function putArtistPictureAction(Request $request, Artist $artist)
    {
        $tmpFile = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];
        file_put_contents($tmpFilePath, $request->getContent());

        // The last parameter (test) allow you to skip some validation steps that fails when
        // the image is not uploaded through a POST HTTP Form
        $file = new UploadedFile($tmpFilePath, 'image.jpg', null, null, null, true);
        $artist->setImageFile($file);

        $errors = $this->get('validator')->validate($artist);
        if (count($errors) > 0) {
            return new Response((string) $errors, 415);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($artist);
        $manager->flush();

        return $artist;
    }

    /**
     * @return \AppBundle\Repository\ArtistRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Artist');
    }
}

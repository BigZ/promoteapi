<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Controller;

use App\Entity\Artist;
use App\Form\Type\ArtistType;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
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
     * @SWG\Response(response=200, description="Get artists",
     *     @SWG\Schema(@Model(type=PaginatedRepresentation::class))
     * )
     *
     * @return PaginatedRepresentation
     */
    public function getArtistsAction()
    {
        return $this->get('bigz_halapi.pagination_factory')->getRepresentation(Artist::class);
    }

    /**
     * Get an artist.
     *
     * @SWG\Response(response=200, description="Get an artist",
     *     @SWG\Schema(@Model(type=Artist::class))
     * )
     * @SWG\Response(response=404, description="Artist not found")
     *
     * @param Artist $artist
     *
     * @return Artist
     */
    public function getArtistAction(Artist $artist)
    {
        return $artist;
    }

    /**
     * Create a new Artist.
     *
     * @SWG\Parameter(
     *     name="artist",
     *     in="body",
     *     description="Artist to add",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="artist", ref=@Model(type=ArtistType::class))
     *     )
     * )
     * @SWG\Response(response=201, description="Artist created", @Model(type=Artist::class))
     * @SWG\Response(response=400, description="Invalid Request")
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

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $artist->setCreatedBy($this->getUser());
            $manager->persist($artist);
            $manager->flush();

            return $this->view($artist, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * Update an Artist.
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Artist to update",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="artist", ref=@Model(type=ArtistType::class))
     *     )
     * )
     * @SWG\Response(response=200, description="Artist updated",
     *     @SWG\Schema(@Model(type=Artist::class))
     * )
     * @SWG\Response(response=400, description="Invalid Request")
     * @SWG\Response(response=404, description="Artist not found")
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

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Artist to patch",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="artist", ref=@Model(type=ArtistType::class))
     *     )
     * )
     * @SWG\Response(response=200, description="Artist updated", @Model(type=Artist::class))
     * @SWG\Response(response=400, description="Invalid Request")
     * @SWG\Response(response=404, description="Artist not found")
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

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @SWG\Response(response=204, description="Artist deleted")
     * @SWG\Response(response=404, description="Artist not found")
     *
     * @Security("is_granted('delete')")
     *
     * @param Artist $artist
     *
     * @return Response
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
     * @SWG\Parameter(
     *     name="BinaryData",
     *     in="body",
     *     description="Image content",
     *     required=true,
     *     @SWG\Schema(type="string", format="byte"),
     * )
     * @SWG\Response(response=200, description="Artist picture updated", @Model(type=Artist::class))
     * @SWG\Response(response=415, description="Unsupported media type")
     * @SWG\Response(response=404, description="Artist not found")
     *
     * @return Artist|Response
     */
    public function putArtistPictureAction(Request $request, Artist $artist)
    {
        $tmpFile = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];
        file_put_contents($tmpFilePath, $request->getContent());

        // The last parameter (test) allow you to skip some validation steps that fails when
        // the image is not uploaded through a POST HTTP Form
        $file = new UploadedFile($tmpFilePath, 'image.jpg');
        $artist->setImageFile($file);

        $errors = $this->get('validator')->validate($artist);
        if (count($errors) > 0) {
            return new Response($errors->get(0)->getMessage(), 415);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($artist);
        $manager->flush();

        return $artist;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('App:Artist');
    }
}

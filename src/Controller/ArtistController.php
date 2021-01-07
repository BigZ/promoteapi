<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Wizards\RestBundle\Controller\JsonControllerTrait;
use WizardsRest\Annotation\Type;
use WizardsRest\CollectionManager;

/**
 * @Type("artist")
 *
 * @Route("/artists")
 */
class ArtistController extends AbstractController
{
    use JsonControllerTrait;

    /**
     * @Route("", methods={"GET"})
     *
     * @OA\Response(
     *     description="Get paginated Artist configurations.",
     *     response=200,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="data",
     *             type="array",
     *             @OA\Items(ref=@Model(type=Artist::class))
     *         )
     *     )
     * )
     */
    public function getArtistsAction(CollectionManager $collectionManager, ServerRequestInterface $request)
    {
        return $collectionManager->getPaginatedCollection(Artist::class, $request);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     * @OA\Response(
     *     description="Get a Artist.",
     *     response=200,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             ref=@Model(type=Artist::class)
     *         )
     *     )
     * )
     */
    public function getArtistAction(string $id, EntityManagerInterface $entityManager)
    {
        try {
            $artist = $entityManager->find(Artist::class, $id);
        } catch (\Exception $exception) {
            throw new NotFoundHttpException('Artist not found.');
        }

        return $artist;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function postArtistAction(Request $request, EntityManagerInterface $entityManager)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $this->handleJsonForm($form, $request);

        if (!$form->isValid()) {
            $this->throwRestErrorFromForm($form);
        }

        $entityManager->persist($artist);
        $entityManager->flush();

        return $artist;
    }

    /**
     * @Route("/{id}", methods={"PATCH"})
     */
    public function patchArtistAction(Artist $artist, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ArtistType::class, $artist, ['method' => 'PATCH']);
        $this->handleJsonForm($form, $request);

        if (!$form->isValid()) {
            $this->throwRestErrorFromForm($form);
        }

        $entityManager->persist($artist);
        $entityManager->flush();

        return $artist;
    }

    /**
     * Delete an Artist.
     *
     * @OA\Response(response=204, description="Artist deleted")
     * @OA\Response(response=404, description="Artist not found")
     */
    public function deleteArtistAction(Artist $artist): Response
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($artist);
        $manager->flush();

        return new Response('{}', 204);
    }
}

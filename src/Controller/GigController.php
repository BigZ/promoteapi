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

use App\Entity\Gig;
use App\Form\Type\GigType;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GigController
 * @author Romain Richard
 */
class GigController extends Controller
{
    use ControllerTrait;

    /**
     * Get all gigs.
     *
     * @SWG\Response(response=200, description="Get the paginated gigs collection",
     *     @SWG\Items(@Model(type=Gig::class))
     * )
     *
     * @return PaginatedRepresentation
     */
    public function getGigsAction()
    {
        return $this->get('bigz_halapi.pagination_factory')->getRepresentation(Gig::class);
    }

    /**
     * Get a gig.
     *
     * @SWG\Response(response=200, description="Get a gig", @Model(type=Gig::class))
     * @SWG\Response(response=404, description="Gig not found")
     *
     * @param Gig $gig
     *
     * @return Gig
     */
    public function getGigAction(Gig $gig)
    {
        return $gig;
    }

    /**
     * Create a new Gig.
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Gig to add",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="gig", ref=@Model(type=GigType::class))
     *     )
     * )
     * @SWG\Response(response=201, description="Gig created", @Model(type=Gig::class))
     * @SWG\Response(response=400, description="Invalid Request")
     *
     * @Security("is_granted('create')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postGigAction(Request $request)
    {
        $gig = new Gig();
        $form = $this->createForm(GigType::class, $gig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $gig->setCreatedBy($this->getUser());
            $manager->persist($gig);
            $manager->flush();

            return $this->view($gig, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * Modify an existing Gig.
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Gig to add",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="gig", ref=@Model(type=GigType::class))
     *     )
     * )
     * @SWG\Response(response=200, description="Gig updated", @Model(type=Gig::class))
     * @SWG\Response(response=400, description="Invalid request")
     * @SWG\Response(response=404, description="Gig not found")
     *
     * @Security("is_granted('edit')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function putGigAction(Request $request, Gig $gig)
    {
        $form = $this->createForm(GigType::class, $gig, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $this->view($form, 400);
    }

    /**
     * Patch an existing Gig.
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Gig to add",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(property="gig", ref=@Model(type=GigType::class))
     *     )
     * )
     * @SWG\Response(response=200, description="Gig patched", @Model(type=Gig::class))
     * @SWG\Response(response=400, description="Invalid request")
     * @SWG\Response(response=404, description="Gig not found")
     *
     * @Security("is_granted('edit')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function patchGigAction(Request $request, Gig $gig)
    {
        $form = $this->createForm(GigType::class, $gig, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $this->view($form, 400);
    }

    /**
     * Delete an existing Gig.
     *
     * @SWG\Response(response=204, description="Gig deleted")
     * @SWG\Response(response=404, description="Gig not found")
     *
     * @Security("is_granted('delete')")
     *
     * @param Gig $gig
     *
     * @return Response
     */
    public function deleteGigAction(Gig $gig)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($gig);
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
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('App:Gig');
    }
}
